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
$paiement_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$paiement_id) {
    header('Location: paiements.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT p.*, f.numero_facture, c.nom AS client_nom, c.ice, c.adresse, c.ville, c.code_postal
        FROM paiements p
        JOIN factures f ON p.facture_id = f.id
        JOIN clients c ON f.client_id = c.id
        WHERE p.id = :id AND p.user_id = :user_id
    ");
    $stmt->bindParam(':id', $paiement_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$paiement) {
        throw new Exception(__('paiement_view.not_found'));
    }
    
    $date_paiement = date('d/m/Y', strtotime($paiement['date_paiement']));
    $montant = number_format($paiement['montant'], 2, ',', ' ');
    
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('paiement_view.title', ['id' => str_pad($paiement_id, 5, '0', STR_PAD_LEFT)]) ?> - efacture-maroc.com</title>
    
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
        
        #paiement-container-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        
        .paiement-container {
            width: 190mm !important;
            min-height: 270mm !important;
            padding: 5mm !important;
            transform: scale(0.98);
            transform-origin: top left;
            font-size: 8px !important;
            background: white;
        }
        
        .paiement-header, 
        .client-info, 
        .payment-terms {
            margin-bottom: 3mm !important;
            padding-bottom: 2mm !important;
        }
        
        .paiement-header {
            border-bottom: 1px solid #333;
            flex-direction: column;
            text-align: center;
        }
        
        .paiement-title h1 {
            color: #333;
            font-size: 16px;
        }
        
        .paiement-details th {
            background-color: #f5f5f5;
            color: #333;
            padding: 1mm !important;
            font-size: 8px;
            line-height: 1.2 !important;
        }
        
        .paiement-details td {
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
        .paiement-title {
            text-align: center;
            width: 100%;
        }
        
        .paiement-totals {
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
            .paiement-container {
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
                <a href="paiements.php" class="inline-flex items-center text-primary hover:text-green-800 transition">
                    <i class="fas fa-arrow-left mr-2"></i> <?= __('paiement_view.back_button') ?>
                </a>
                <div class="flex space-x-3">
                    <button onclick="generatePDF()" 
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-file-pdf mr-2"></i> <?= __('paiement_view.download_pdf') ?>
                    </button>
                    <a href="paiement_edit.php?id=<?= $paiement_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-edit mr-2"></i> <?= __('paiement_view.edit_button') ?>
                    </a>
                    <a href="paiements.php?delete=<?= $paiement_id ?>" 
                       class="inline-flex items-center px-4 py-2 bg-secondary hover:bg-red-800 text-white rounded-md shadow-sm font-medium transition"
                       onclick="return confirm('<?= __('paiement_view.delete_confirm') ?>')">
                        <i class="fas fa-trash-alt mr-2"></i> <?= __('paiement_view.delete_button') ?>
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
                <div id="paiement-container-wrapper">
                    <div id="paiement-container" class="paiement-container">
                        <div class="paiement-header">
                            <div class="company-info mb-3">
                                <img src="assets/images/logo.png" alt="Logo" style="width:80px; height:auto; image-rendering: crisp-edges;">
                                <h2 class="text-lg font-bold text-gray-800"><?= SITE_NAME ?></h2>
                                <!-- Correction: Remplacer les constantes non définies par des valeurs statiques -->
                                <p class="text-gray-600"><?= __('paiement_view.company.ice') ?>: VOTRE_ICE_ICI</p>
                                <p class="text-gray-600"><?= __('paiement_view.company.address') ?>: Votre adresse</p>
                                <p class="text-gray-600"><?= __('paiement_view.company.phone') ?>: Votre téléphone</p>
                                <p class="text-gray-600"><?= __('paiement_view.company.email') ?>: contact@efacture-maroc.com</p>
                            </div>
                            
                            <div class="paiement-title">
                                <h1 class="text-xl font-bold text-gray-700 mb-1"><?= __('paiement_view.receipt_title') ?></h1>
                                <p class="text-gray-700"><?= __('paiement_view.receipt_number', ['id' => str_pad($paiement_id, 5, '0', STR_PAD_LEFT)]) ?></p>
                                <p class="text-gray-600"><?= __('paiement_view.date') ?>: <?= $date_paiement ?></p>
                            </div>
                        </div>
                        
                        <div class="client-info bg-gray-50">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('paiement_view.received_from') ?></h3>
                            <p class="text-gray-800 font-medium"><?= htmlspecialchars($paiement['client_nom']) ?></p>
                            <p class="text-gray-600"><?= __('paiement_view.client.ice') ?>: <?= htmlspecialchars($paiement['ice']) ?></p>
                            <p class="text-gray-600"><?= __('paiement_view.client.address') ?>: <?= htmlspecialchars($paiement['adresse']) ?></p>
                            <p class="text-gray-600"><?= htmlspecialchars($paiement['ville']) ?> - <?= htmlspecialchars($paiement['code_postal']) ?></p>
                        </div>
                        
                        <table class="paiement-details w-full mt-4">
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="px-1 py-1 text-gray-800 font-medium w-1/3"><?= __('paiement_view.invoice_concerned') ?></td>
                                    <td class="px-1 py-1 text-gray-600">FAC-<?= str_pad($paiement['facture_id'], 5, '0', STR_PAD_LEFT) ?></td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-1 py-1 text-gray-800 font-medium"><?= __('paiement_view.amount_paid') ?></td>
                                    <td class="px-1 py-1 text-gray-600"><?= $montant ?> DH</td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-1 py-1 text-gray-800 font-medium"><?= __('paiement_view.payment_method') ?></td>
                                    <td class="px-1 py-1 text-gray-600"><?= htmlspecialchars($paiement['mode_paiement']) ?></td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-1 py-1 text-gray-800 font-medium"><?= __('paiement_view.reference') ?></td>
                                    <td class="px-1 py-1 text-gray-600"><?= htmlspecialchars($paiement['reference']) ?></td>
                                </tr>
                                <tr class="border-b border-gray-200">
                                    <td class="px-1 py-1 text-gray-800 font-medium"><?= __('paiement_view.notes') ?></td>
                                    <td class="px-1 py-1 text-gray-600"><?= htmlspecialchars($paiement['notes'] ?? __('paiement_view.no_notes')) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="paiement-totals ml-auto mt-6">
                            <div class="total-ttc py-2 px-4 text-center">
                                <span class="font-bold text-primary"><?= __('paiement_view.total_amount') ?>: <?= $montant ?> DH</span>
                            </div>
                        </div>
                        
                        <div class="payment-terms bg-gray-50 mt-4">
                            <h3 class="font-bold text-gray-800 mb-1"><?= __('paiement_view.bank_details') ?></h3>
                            <!-- Correction: Remplacer les constantes non définies par des valeurs statiques -->
                            <p class="text-gray-600"><?= __('paiement_view.bank') ?>: Votre banque</p>
                            <p class="text-gray-600"><?= __('paiement_view.rib') ?>: Votre RIB</p>
                            <p class="text-gray-600"><?= __('paiement_view.swift_code') ?>: Votre code SWIFT</p>
                        </div>
                        
                        <div class="legal-mentions mt-4">
                            <p><strong><?= __('paiement_view.legal_mentions.title') ?>:</strong> <?= __('paiement_view.legal_mentions.content') ?></p>
                        </div>
                        
                        <div class="signature text-right mt-6">
                            <p class="text-gray-600"><?= __('paiement_view.date_signed', ['date' => $date_paiement]) ?></p>
                            <p class="text-gray-600 mt-2"><?= __('paiement_view.signature') ?></p>
                            <div class="signature-line inline-block"></div>
                            <p class="text-gray-500 mt-1"><?= __('paiement_view.signature_note') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        function generatePDF() {
            const element = document.getElementById('paiement-container');
            
            const options = {
                margin: [5, 5, 5, 5],
                filename: 'paiement-<?= $paiement_id ?>.pdf',
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
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> <?= __('paiement_view.generating_pdf') ?>';
            btn.disabled = true;
            
            html2pdf().set(options).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>