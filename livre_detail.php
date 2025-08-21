<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$periode = $_GET['periode'] ?? '';
$user_id = $_SESSION['user']['id'];

if (empty($periode)) {
    header('Location: livres_comptables.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            date,
            type,
            id,
            reference,
            client_nom,
            montant,
            statut
        FROM (
            SELECT 
                f.date_facture AS date, 
                'facture' AS type,
                f.id,
                f.numero_facture AS reference,
                c.nom AS client_nom,
                (f.montant_ht * (1 + f.taux_tva/100)) AS montant,
                f.statut
            FROM factures f
            JOIN clients c ON f.client_id = c.id
            WHERE f.user_id = ?
            AND DATE_FORMAT(f.date_facture, '%Y-%m') = ?
            
            UNION ALL
            
            SELECT 
                d.date_creation AS date, 
                'devis' AS type,
                d.id,
                d.numero_devis AS reference,
                c.nom AS client_nom,
                (d.montant_ht * (1 + d.taux_tva/100)) AS montant,
                d.statut
            FROM devis d
            JOIN clients c ON d.client_id = c.id
            WHERE d.user_id = ?
            AND DATE_FORMAT(d.date_creation, '%Y-%m') = ?
            
            UNION ALL
            
            SELECT 
                p.date_paiement AS date, 
                'paiement' AS type,
                p.id,
                p.reference AS reference,
                c.nom AS client_nom,
                p.montant AS montant,
                NULL AS statut
            FROM paiements p
            JOIN factures f ON p.facture_id = f.id
            JOIN clients c ON f.client_id = c.id
            WHERE p.user_id = ?
            AND DATE_FORMAT(p.date_paiement, '%Y-%m') = ?
        ) AS transactions
        ORDER BY date DESC
    ");
    $stmt->execute([$user_id, $periode, $user_id, $periode, $user_id, $periode]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $transactions = [];
    $error = __("error.db_error") . $e->getMessage();
}

$dateObj = DateTime::createFromFormat('Y-m', $periode);
$mois = [
    1 => __('livre_detail.months.january'),
    2 => __('livre_detail.months.february'),
    3 => __('livre_detail.months.march'),
    4 => __('livre_detail.months.april'),
    5 => __('livre_detail.months.may'),
    6 => __('livre_detail.months.june'),
    7 => __('livre_detail.months.july'),
    8 => __('livre_detail.months.august'),
    9 => __('livre_detail.months.september'),
    10 => __('livre_detail.months.october'),
    11 => __('livre_detail.months.november'),
    12 => __('livre_detail.months.december')
];
$mois_num = (int)$dateObj->format('n');
$formatted_periode = $mois[$mois_num] . ' ' . $dateObj->format('Y');
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('livre_detail.title') ?> - efacture-maroc.com</title>
    
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
        .bg-facture { background-color: #f0f9ff; }
        .bg-devis { background-color: #f6ffed; }
        .bg-paiement { background-color: #f0f0f0; }
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee { background-color: #e6f7ff; color: #1890ff; }
        .status-payee { background-color: #f6ffed; color: #52c41a; }
        .status-impayee { background-color: #fff2f0; color: #ff4d4f; }
        .status-accepte { background-color: #f6ffed; color: #52c41a; }
        .status-refuse { background-color: #fff2f0; color: #ff4d4f; }
        .table-row:hover { background-color: #f9f9f9; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= __('livre_detail.title') ?></h1>
                    <p class="text-gray-600"><?= $formatted_periode ?></p>
                </div>
                <a href="livres_comptables.php" class="text-primary hover:text-green-700 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> <?= __('livre_detail.back_button') ?>
                </a>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.date') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.type') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.reference') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.client') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.amount') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('livre_detail.table.status') ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transaction): 
                                    $date = DateTime::createFromFormat('Y-m-d', $transaction['date'])->format('d/m/Y');
                                    $bg_class = 'bg-' . $transaction['type'];
                                    $status_class = $transaction['type'] === 'paiement' ? '' : 'status-' . $transaction['statut'];
                                ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $date ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs <?= $bg_class ?>">
                                                <?= __("livre_detail.types." . $transaction['type']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= $transaction['reference'] ?: __('livre_detail.na') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($transaction['client_nom']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= number_format($transaction['montant'], 2, ',', ' ') ?> <?= __('livre_detail.currency') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($transaction['type'] !== 'paiement'): ?>
                                                <span class="px-2 py-1 rounded-full text-xs <?= $status_class ?>">
                                                    <?= __("livre_detail.status." . $transaction['statut']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                                            <p><?= __('livre_detail.no_transactions') ?></p>
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