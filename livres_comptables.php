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
$user_name = isset($_SESSION['user']['nom']) ? $_SESSION['user']['nom'] : $_SESSION['user']['email'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(date, '%Y-%m') AS periode,
            COUNT(*) AS total,
            SUM(CASE WHEN type = 'facture' THEN 1 ELSE 0 END) AS factures,
            SUM(CASE WHEN type = 'devis' THEN 1 ELSE 0 END) AS devis,
            SUM(CASE WHEN type = 'paiement' THEN 1 ELSE 0 END) AS paiements,
            SUM(montant) AS montant_total
        FROM (
            SELECT date_facture AS date, 'facture' AS type, montant_ht * (1 + taux_tva/100) AS montant
            FROM factures 
            WHERE user_id = ?
            UNION ALL
            SELECT date_creation AS date, 'devis' AS type, montant_ht * (1 + taux_tva/100) AS montant
            FROM devis 
            WHERE user_id = ?
            UNION ALL
            SELECT date_paiement AS date, 'paiement' AS type, montant
            FROM paiements 
            WHERE user_id = ?
        ) AS transactions
        GROUP BY periode
        ORDER BY periode DESC
    ");
    $stmt->execute([$user_id, $user_id, $user_id]);
    $periodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $periodes = [];
    $error = __("error.db_error") . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('livres_comptables.title') ?> - efacture-maroc.com</title>
    
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .table-row:hover {
            background-color: #f9f9f9;
        }
        
        .bg-facture { background-color: #f0f9ff; }
        .bg-devis { background-color: #f6ffed; }
        .bg-paiement { background-color: #f0f0f0; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= __('livres_comptables.title') ?></h1>
                    <p class="text-gray-600"><?= __('livres_comptables.subtitle') ?></p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.period') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.invoices') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.quotes') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.payments') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.total_amount') ?></th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livres_comptables.table.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($periodes)): ?>
                                <?php foreach ($periodes as $periode): 
                                    $dateObj = DateTime::createFromFormat('Y-m', $periode['periode']);
                                    $monthName = $dateObj->format('F Y');
                                ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900"><?= $monthName ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full bg-facture">
                                                <?= $periode['factures'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full bg-devis">
                                                <?= $periode['devis'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full bg-paiement">
                                                <?= $periode['paiements'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 font-medium">
                                            <?= number_format($periode['montant_total'], 2, ',', ' ') ?> DH
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="livre_detail.php?periode=<?= $periode['periode'] ?>" class="text-primary hover:text-green-700">
                                                <i class="fas fa-eye mr-1"></i> <?= __('livres_comptables.table.view_details') ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <i class="fas fa-book-open text-4xl text-gray-300 mb-3"></i>
                                            <p><?= __('livres_comptables.empty_message') ?></p>
                                            <p class="text-sm mt-2"><?= __('livres_comptables.empty_submessage') ?></p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>