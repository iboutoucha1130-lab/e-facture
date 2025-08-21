<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$user_id = $_SESSION['user']['id'];
$devis_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$devis_id) {
    header('Location: devis.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT d.*, c.nom AS client_nom, c.ice, c.adresse, c.ville, c.code_postal, c.telephone, c.email AS client_email
        FROM devis d
        JOIN clients c ON d.client_id = c.id
        WHERE d.id = :id AND d.user_id = :user_id
    ");
    $stmt->bindParam(':id', $devis_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $devis = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$devis) {
        throw new Exception(__('devis_view.not_found'));
    }
    
    $stmt = $pdo->prepare("
        SELECT dl.*, p.nom AS produit_nom 
        FROM devis_lignes dl
        LEFT JOIN produits p ON dl.produit_id = p.id
        WHERE dl.devis_id = :devis_id
    ");
    $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
    $stmt->execute();
    $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $montant_ht = (float)$devis['montant_ht'];
    $tva = (float)$devis['taux_tva'];
    $montant_tva = $montant_ht * ($tva / 100);
    $montant_ttc = $montant_ht + $montant_tva;
    
    $date_creation = date('d/m/Y', strtotime($devis['date_creation']));
    $date_validite = date('d/m/Y', strtotime($devis['date_validite']));
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('devis_view.title', ['id' => str_pad($devis_id, 5, '0', STR_PAD_LEFT)]) ?> - efacture-maroc.com</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006233',
                        secondary: '#C1272D',
                        accent: '#0a9c5e',
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shadcn/ui/dist/shadcn-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        #devis-container-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .devis-container {
            width: 190mm !important;
            min-height: 270mm !important;
            padding: 5mm !important;
            transform: scale(0.98);
            transform-origin: top left;
            font-size: 8px !important;
            background: white;
        }
        
        .devis-header, 
        .client-info, 
        .validity-terms {
            margin-bottom: 3mm !important;
            padding-bottom: 2mm !important;
        }
        
        .devis-header {
            border-bottom: 1px solid #333;
            flex-direction: column;
            text-align: center;
        }
        
        .devis-title h1 {
            color: #333;
            font-size: 16px;
        }
        
        .devis-details th {
            background-color: #f5f5f5;
            color: #333;
            padding: 1mm !important;
            font-size: 8px;
            line-height: 1.2 !important;
        }
        
        .devis-details td {
            padding: 1mm !important;
            line-height: 1.2 !important;
        }
        
        .total-ttc {
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            font-size: 9px;
        }
        
        .signature-line {
            border-top: 1px dashed #333;
            width: 150px;
        }
        
        .client-info, .validity-terms {
            padding: 2mm;
            font-size: 8px;
        }

        .company-info, 
        .devis-title {
            text-align: center;
            width: 100%;
        }
        
        .devis-totals {
            width: 50%;
        }
        
        .legal-mentions {
            font-size: 7px;
        }
        
        .signature {
            font-size: 8px;
        }
        
        @media print {
            body {
                background-color: white !important;
            }
            .no-print {
                display: none !important;
            }
        }
        
        @media (min-width: 1024px) {
            .devis-container {
                margin: 0 auto;
            }
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee { background-color: #e6f7ff; color: #1890ff; }
        .status-accepte { background-color: #f6ffed; color: #52c41a; }
        .status-refuse { background-color: #fff2f0; color: #ff4d4f; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print">
                <a href="devis.php" class="inline-flex items-center text-primary hover:text-green-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> <?= __('devis_view.back_button') ?>
                </a>
                <div class="flex space-x-3">
                    <button onclick="generatePDF()" 
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-file-pdf mr-2"></i> <?= __('devis_view.download_pdf') ?>
                    </button>
                    <a href="devis_edit.php?id=<?= $devis_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-edit mr-2"></i> <?= __('devis_view.edit_button') ?>
                    </a>
                    <a href="devis.php?delete=<?= $devis_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-secondary hover:bg-red-800 text-white rounded-md shadow-sm font-medium transition"
                       onclick="return confirm('<?= __('devis_view.delete_confirm') ?>')">
                        <i class="fas fa-trash-alt mr-2"></i> <?= __('devis_view.delete_button') ?>
                    </a>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800"><?= $error ?></h3>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div id="devis-container-wrapper">
                    <div id="devis-container" class="devis-container">
                        <div class="devis-header">
                            <div class="company-info mb-3">
                                <img src="assets/images/logo.png" alt="Logo" style="width:80px; height:auto; image-rendering: crisp-edges;">
                                <h2 class="text-lg font-bold text-gray-800"><?= SITE_NAME ?></h2>
                                <p class="text-gray-600"><?= __('devis_view.company_ice') ?>: VOTRE_ICE_ICI</p>
                                <p class="text-gray-600"><?= __('devis_view.company_address') ?>: Votre adresse</p>
                                <p class="text-gray-600"><?= __('devis_view.company_phone') ?>: Votre téléphone</p>
                                <p class="text-gray-600"><?= __('devis_view.company_email') ?>: contact@efacture-maroc.com</p>
                            </div>
                            
                            <div class="devis-title">
                                <h1 class="text-xl font-bold text-gray-700 mb-1"><?= __('devis_view.document_title') ?></h1>
                                <p class="text-gray-700"><?= __('devis_view.document_number') ?> DEV-<?= str_pad($devis_id, 5, '0', STR_PAD_LEFT) ?></p>
                                <p class="text-gray-600"><?= __('devis_view.date') ?>: <?= $date_creation ?></p>
                                <p class="text-gray-600 mt-1">
                                    <?= __('devis_view.status') ?>: 
                                    <span class="status-badge status-<?= $devis['statut'] ?>">
                                        <?= ucfirst($devis['statut']) ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="client-info bg-gray-50">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('devis_view.recipient') ?>:</h3>
                            <p class="text-gray-800 font-medium"><?= htmlspecialchars($devis['client_nom']) ?></p>
                            <p class="text-gray-600"><?= __('devis_view.ice') ?>: <?= htmlspecialchars($devis['ice']) ?></p>
                            <p class="text-gray-600"><?= __('devis_view.address') ?>: <?= htmlspecialchars($devis['adresse']) ?></p>
                            <p class="text-gray-600"><?= htmlspecialchars($devis['ville']) ?> - <?= htmlspecialchars($devis['code_postal']) ?></p>
                            <p class="text-gray-600"><?= __('devis_view.phone') ?>: <?= htmlspecialchars($devis['telephone']) ?></p>
                            <p class="text-gray-600"><?= __('devis_view.email') ?>: <?= htmlspecialchars($devis['client_email']) ?></p>
                        </div>
                        
                        <table class="devis-details w-full">
                            <thead>
                                <tr>
                                    <th class="px-1 py-1 text-left"><?= __('devis_view.table.description') ?></th>
                                    <th class="px-1 py-1 text-right"><?= __('devis_view.table.quantity') ?></th>
                                    <th class="px-1 py-1 text-right"><?= __('devis_view.table.unit_price') ?> (DH)</th>
                                    <th class="px-1 py-1 text-right"><?= __('devis_view.table.total') ?> (DH)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lignes as $ligne): ?>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-1 py-1 text-gray-800">
                                            <?= $ligne['produit_nom'] ? htmlspecialchars($ligne['produit_nom']) : htmlspecialchars($ligne['description']) ?>
                                        </td>
                                        <td class="px-1 py-1 text-gray-600 text-right"><?= number_format($ligne['quantite'], 2, ',', ' ') ?></td>
                                        <td class="px-1 py-1 text-gray-600 text-right"><?= number_format($ligne['prix_unitaire'], 2, ',', ' ') ?></td>
                                        <td class="px-1 py-1 text-gray-800 font-medium text-right"><?= number_format($ligne['quantite'] * $ligne['prix_unitaire'], 2, ',', ' ') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="devis-totals ml-auto mt-2">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600"><?= __('devis_view.total_ht') ?>:</span>
                                    <span class="text-gray-800 font-medium"><?= number_format($montant_ht, 2, ',', ' ') ?> DH</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600"><?= __('devis_view.vat') ?> (<?= $tva ?>%):</span>
                                    <span class="text-gray-800 font-medium"><?= number_format($montant_tva, 2, ',', ' ') ?> DH</span>
                                </div>
                                <div class="total-ttc flex justify-between py-1">
                                    <span class="font-bold text-gray-800"><?= __('devis_view.total_ttc') ?>:</span>
                                    <span class="font-bold text-primary"><?= number_format($montant_ttc, 2, ',', ' ') ?> DH</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="validity-terms bg-gray-50 mt-2">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('devis_view.validity_conditions') ?></h3>
                            <p class="text-gray-600"><?= __('devis_view.validity_date') ?>: <?= $date_validite ?></p>
                            <p class="text-gray-600"><?= __('devis_view.validity_text') ?></p>
                        </div>
                        
                        <div class="legal-mentions mt-2">
                            <p class="mb-1"><strong><?= __('devis_view.legal_notice') ?>:</strong> <?= __('devis_view.legal_conformity') ?></p>
                            <p><?= __('devis_view.legal_note') ?></p>
                        </div>
                        
                        <div class="signature text-right mt-3">
                            <p class="text-gray-600"><?= __('devis_view.date_prefix') ?> <?= $date_creation ?></p>
                            <p class="text-gray-600 mt-2"><?= __('devis_view.signature') ?></p>
                            <div class="signature-line inline-block"></div>
                            <p class="text-gray-500 mt-1"><?= __('devis_view.signature_note') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        function generatePDF() {
            const element = document.getElementById('devis-container');
            
            const options = {
                margin: [5, 5, 5, 5],
                filename: 'devis-<?= $devis_id ?>.pdf',
                image: { 
                    type: 'jpeg', 
                    quality: 0.98 
                },
                html2canvas: { 
                    scale: 4,
                    dpi: 600,
                    letterRendering: true,
                    useCORS: true,
                    logging: false,
                    onclone: (clonedDoc) => {
                        clonedDoc.querySelectorAll('img').forEach(img => {
                            img.style.maxWidth = '100px';
                            img.style.height = 'auto';
                        });
                    },
                    ignoreElements: (element) => {
                        return element.innerText.trim() === "" && 
                               element.children.length === 0;
                    }
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait',
                    compress: true
                }
            };
            
            const btn = document.querySelector('button[onclick="generatePDF()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> <?= __('devis_view.generating_pdf') ?>';
            btn.disabled = true;
            
            html2pdf().set(options).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>