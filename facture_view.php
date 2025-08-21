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
$facture_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$facture_id) {
    header('Location: factures.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT f.*, c.nom AS client_nom, c.ice, c.adresse, c.ville, c.code_postal, c.telephone, c.email AS client_email
        FROM factures f
        JOIN clients c ON f.client_id = c.id
        WHERE f.id = :id AND f.user_id = :user_id
    ");
    $stmt->bindParam(':id', $facture_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $facture = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$facture) {
        throw new Exception(__('facture_view.error_not_found'));
    }
    
    $stmt = $pdo->prepare("
        SELECT fl.*, p.nom AS produit_nom 
        FROM facture_lignes fl
        LEFT JOIN produits p ON fl.produit_id = p.id
        WHERE fl.facture_id = :facture_id
    ");
    $stmt->bindParam(':facture_id', $facture_id, PDO::PARAM_INT);
    $stmt->execute();
    $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $montant_ht = (float)$facture['montant_ht'];
    $tva = (float)$facture['taux_tva'];
    $montant_tva = $montant_ht * ($tva / 100);
    $montant_ttc = $montant_ht + $montant_tva;
    
    $date_facture = date('d/m/Y', strtotime($facture['date_facture']));
    $date_echeance = date('d/m/Y', strtotime($facture['date_echeance']));
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('facture_view.title', ['id' => str_pad($facture_id, 5, '0', STR_PAD_LEFT)]) ?></title>
    
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
        
        #facture-container-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .facture-container {
            width: 190mm !important;
            min-height: 270mm !important;
            padding: 5mm !important;
            transform: scale(0.98);
            transform-origin: top left;
            font-size: 8px !important;
            background: white;
        }
        
        .facture-header, 
        .client-info, 
        .payment-terms {
            margin-bottom: 3mm !important;
            padding-bottom: 2mm !important;
        }
        
        .facture-header {
            border-bottom: 1px solid #333;
            flex-direction: column;
            text-align: center;
        }
        
        .facture-title h1 {
            color: #333;
            font-size: 16px;
        }
        
        .facture-details th {
            background-color: #f5f5f5;
            color: #333;
            padding: 1mm !important;
            font-size: 8px;
            line-height: 1.2 !important;
        }
        
        .facture-details td {
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
        
        .client-info, .payment-terms {
            padding: 2mm;
            font-size: 8px;
        }

        .company-info, 
        .facture-title {
            text-align: center;
            width: 100%;
        }
        
        .facture-totals {
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
            .facture-container {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print">
                <a href="factures.php" class="inline-flex items-center text-primary hover:text-green-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> <?= __('facture_view.back_button') ?>
                </a>
                <div class="flex space-x-3">
                    <button onclick="generatePDF()" 
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-file-pdf mr-2"></i> <?= __('facture_view.download_pdf') ?>
                    </button>
                    <a href="facture_edit.php?id=<?= $facture_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-edit mr-2"></i> <?= __('facture_view.edit_button') ?>
                    </a>
                    <a href="factures.php?delete=<?= $facture_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-secondary hover:bg-red-800 text-white rounded-md shadow-sm font-medium transition"
                       onclick="return confirm('<?= __('facture_view.delete_confirm') ?>')">
                        <i class="fas fa-trash-alt mr-2"></i> <?= __('facture_view.delete_button') ?>
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
                <div id="facture-container-wrapper">
                    <div id="facture-container" class="facture-container">
                        <div class="facture-header">
                            <div class="company-info mb-3">
                                <img src="assets/images/logo.png" alt="Logo" style="width:80px; height:auto; image-rendering: crisp-edges;">
                                <h2 class="text-lg font-bold text-gray-800"><?= SITE_NAME ?></h2>
                                <!-- Valeurs fixes comme dans devis_view.php -->
                                <p class="text-gray-600"><?= __('facture_view.company.ice') ?>: VOTRE_ICE_ICI</p>
                                <p class="text-gray-600"><?= __('facture_view.company.address') ?>: Votre adresse</p>
                                <p class="text-gray-600"><?= __('facture_view.company.phone') ?>: Votre téléphone</p>
                                <p class="text-gray-600"><?= __('facture_view.company.email') ?>: contact@efacture-maroc.com</p>
                            </div>
                            
                            <div class="facture-title">
                                <h1 class="text-xl font-bold text-gray-700 mb-1"><?= __('facture_view.invoice') ?></h1>
                                <p class="text-gray-700"><?= __('facture_view.number') ?> FAC-<?= str_pad($facture_id, 5, '0', STR_PAD_LEFT) ?></p>
                                <p class="text-gray-600"><?= __('facture_view.date') ?>: <?= $date_facture ?></p>
                            </div>
                        </div>
                        
                        <div class="client-info bg-gray-50">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('facture_view.billed_to') ?></h3>
                            <p class="text-gray-800 font-medium"><?= htmlspecialchars($facture['client_nom']) ?></p>
                            <p class="text-gray-600"><?= __('facture_view.ice') ?>: <?= htmlspecialchars($facture['ice']) ?></p>
                            <p class="text-gray-600"><?= __('facture_view.address') ?>: <?= htmlspecialchars($facture['adresse']) ?></p>
                            <p class="text-gray-600"><?= htmlspecialchars($facture['ville']) ?> - <?= htmlspecialchars($facture['code_postal']) ?></p>
                            <p class="text-gray-600"><?= __('facture_view.phone') ?>: <?= htmlspecialchars($facture['telephone']) ?></p>
                            <p class="text-gray-600"><?= __('facture_view.email') ?>: <?= htmlspecialchars($facture['client_email']) ?></p>
                        </div>
                        
                        <table class="facture-details w-full">
                            <thead>
                                <tr>
                                    <th class="px-1 py-1 text-left"><?= __('facture_view.table.description') ?></th>
                                    <th class="px-1 py-1 text-right"><?= __('facture_view.table.quantity') ?></th>
                                    <th class="px-1 py-1 text-right"><?= __('facture_view.table.unit_price') ?></th>
                                    <th class="px-1 py-1 text-right"><?= __('facture_view.table.total') ?></th>
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
                        
                        <div class="facture-totals ml-auto mt-2">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600"><?= __('facture_view.total_ht') ?>:</span>
                                    <span class="text-gray-800 font-medium"><?= number_format($montant_ht, 2, ',', ' ') ?> DH</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600"><?= __('facture_view.vat') ?> (<?= $tva ?>%):</span>
                                    <span class="text-gray-800 font-medium"><?= number_format($montant_tva, 2, ',', ' ') ?> DH</span>
                                </div>
                                <div class="total-ttc flex justify-between py-1">
                                    <span class="font-bold text-gray-800"><?= __('facture_view.total_ttc') ?>:</span>
                                    <span class="font-bold text-primary"><?= number_format($montant_ttc, 2, ',', ' ') ?> DH</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-terms bg-gray-50 mt-2">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('facture_view.payment_terms') ?></h3>
                            <p class="text-gray-600"><?= __('facture_view.due_date') ?>: <?= $date_echeance ?></p>
                            <p class="text-gray-600"><?= __('facture_view.payment_method') ?>: <?= __('facture_view.bank_transfer') ?></p>
                            <!-- Valeur fixe pour le RIB -->
                            <p class="text-gray-600"><?= __('facture_view.rib') ?>: VOTRE RIB ICI</p>
                        </div>
                        
                        <div class="legal-mentions mt-2">
                            <p class="mb-1"><strong><?= __('facture_view.legal_notice') ?>:</strong> <?= __('facture_view.legal_text') ?></p>
                            <p><?= __('facture_view.payment_conditions') ?></p>
                        </div>
                        
                        <div class="signature text-right mt-3">
                            <p class="text-gray-600"><?= __('facture_view.date') ?> <?= $date_facture ?></p>
                            <p class="text-gray-600 mt-2"><?= __('facture_view.signature') ?></p>
                            <div class="signature-line inline-block"></div>
                            <p class="text-gray-500 mt-1"><?= __('facture_view.stamp_and_signature') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        function generatePDF() {
            const element = document.getElementById('facture-container');
            
            const options = {
                margin: [5, 5, 5, 5],
                filename: '<?= __('facture_view.pdf_filename', ['id' => $facture_id]) ?>',
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
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> <?= __('facture_view.generating_pdf') ?>';
            btn.disabled = true;
            
            html2pdf().set(options).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>