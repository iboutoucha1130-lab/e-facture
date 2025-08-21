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
$user_email = $_SESSION['user']['email'];
$user_name = isset($_SESSION['user']['nom']) ? $_SESSION['user']['nom'] : $_SESSION['user']['email'];

try {
    $stmt_invoices = $pdo->prepare("SELECT COUNT(*) FROM factures WHERE user_id = ?");
    $stmt_invoices->execute([$user_id]);
    $invoice_count = $stmt_invoices->fetchColumn();

    $stmt_quotes = $pdo->prepare("SELECT COUNT(*) FROM devis WHERE user_id = ?");
    $stmt_quotes->execute([$user_id]);
    $quote_count = $stmt_quotes->fetchColumn();

    $stmt_clients = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE user_id = ?");
    $stmt_clients->execute([$user_id]);
    $client_count = $stmt_clients->fetchColumn();

    $stmt_payments = $pdo->prepare("SELECT COUNT(*) FROM paiements WHERE user_id = ?");
    $stmt_payments->execute([$user_id]);
    $payment_count = $stmt_payments->fetchColumn();

    $stmt_prospects = $pdo->prepare("SELECT COUNT(*) FROM prospects WHERE user_id = ?");
    $stmt_prospects->execute([$user_id]);
    $prospect_count = $stmt_prospects->fetchColumn();

    $stmt_responsables = $pdo->prepare("SELECT COUNT(*) FROM responsables WHERE user_id = ?");
    $stmt_responsables->execute([$user_id]);
    $responsable_count = $stmt_responsables->fetchColumn();

    $stmt_products = $pdo->prepare("SELECT COUNT(*) FROM produits WHERE user_id = ?");
    $stmt_products->execute([$user_id]);
    $product_count = $stmt_products->fetchColumn();

    $stmt_books = $pdo->prepare("
        SELECT (
            (SELECT COUNT(*) FROM factures WHERE user_id = ?) +
            (SELECT COUNT(*) FROM devis WHERE user_id = ?) +
            (SELECT COUNT(*) FROM paiements WHERE user_id = ?)
        ) AS total_transactions
    ");
    $stmt_books->execute([$user_id, $user_id, $user_id]);
    $book_count = $stmt_books->fetchColumn();

    $stmt_stock = $pdo->prepare("SELECT COUNT(*) FROM stock WHERE user_id = ?");
    $stmt_stock->execute([$user_id]);
    $stock_count = $stmt_stock->fetchColumn();
    
    $stmt_recent = $pdo->prepare("
        (SELECT 'facture' AS type, f.id, c.nom AS client_nom, f.date_facture AS date_emission, 
        (f.montant_ht * (1 + f.taux_tva/100)) AS montant_total, f.statut, f.date_echeance AS date_validite, NULL AS date_paiement
        FROM factures f JOIN clients c ON f.client_id = c.id 
        WHERE f.user_id = ? AND f.statut = 'payee' ORDER BY f.date_facture DESC LIMIT 5)
        
        UNION ALL
        
        (SELECT 'devis' AS type, d.id, c.nom AS client_nom, d.date_creation AS date_emission, 
        (d.montant_ht * (1 + d.taux_tva/100)) AS montant_total, d.statut, d.date_validite, NULL AS date_paiement
        FROM devis d JOIN clients c ON d.client_id = c.id 
        WHERE d.user_id = ? ORDER BY d.date_creation DESC LIMIT 5)
        
        UNION ALL
        
        (SELECT 'paiement' AS type, p.id, c.nom AS client_nom, p.date_paiement AS date_emission, 
        p.montant AS montant_total, NULL AS statut, NULL AS date_validite, p.date_paiement
        FROM paiements p JOIN factures f ON p.facture_id = f.id
        JOIN clients c ON f.client_id = c.id 
        WHERE p.user_id = ? ORDER BY p.date_paiement DESC LIMIT 5)
        
        ORDER BY date_emission DESC LIMIT 5
    ");
    $stmt_recent->execute([$user_id, $user_id, $user_id]);
    $recent_activity = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_ca = $pdo->prepare("
        SELECT 
            mois,
            COALESCE(SUM(ca_ttc), 0) AS ca_ttc,
            COALESCE(SUM(nombre_operations), 0) AS nombre_operations
        FROM (
            SELECT DISTINCT DATE_FORMAT(date_field, '%Y-%m') AS mois
            FROM (
                SELECT date_facture AS date_field FROM factures WHERE user_id = ?
                UNION ALL
                SELECT date_paiement FROM paiements WHERE user_id = ?
            ) AS all_dates
            ORDER BY mois DESC
            LIMIT 6
        ) AS last_months
        LEFT JOIN (
            SELECT 
                DATE_FORMAT(f.date_facture, '%Y-%m') AS mois,
                (f.montant_ht * (1 + f.taux_tva/100)) AS ca_ttc,
                1 AS nombre_operations
            FROM factures f
            WHERE f.user_id = ? AND f.statut = 'payee'
            
            UNION ALL
            
            SELECT 
                DATE_FORMAT(p.date_paiement, '%Y-%m') AS mois,
                p.montant AS ca_ttc,
                1 AS nombre_operations
            FROM paiements p
            WHERE p.user_id = ?
        ) AS combined_data USING (mois)
        GROUP BY mois 
        ORDER BY mois ASC
    ");
    $stmt_ca->execute([$user_id, $user_id, $user_id, $user_id]);
    $ca_data = $stmt_ca->fetchAll(PDO::FETCH_ASSOC);
    
    $chart_months = [];
    $chart_ca = [];
    $chart_operations = [];
    
    foreach ($ca_data as $row) {
        $dateObj = DateTime::createFromFormat('Y-m', $row['mois']);
        $chart_months[] = $dateObj->format('M Y');
        $chart_ca[] = (float) $row['ca_ttc'];
        $chart_operations[] = (int) $row['nombre_operations'];
    }
    
    if (empty($chart_months)) {
        $chart_months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
        $chart_ca = array_fill(0, 6, 0);
        $chart_operations = array_fill(0, 6, 0);
    }
    
} catch (PDOException $e) {
    $invoice_count = $quote_count = $client_count = $payment_count = $prospect_count = $responsable_count = $product_count = $book_count = $stock_count = 0;
    $recent_activity = [];
    $chart_months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
    $chart_ca = $chart_operations = array_fill(0, 6, 0);
    $error = __("error.db_error") . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('dashboard.title') ?> - efacture-maroc.com</title>
    
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee, .status-en-cours { background-color: #e6f7ff; color: #1890ff; }
        .status-payee, .status-accepte { background-color: #f6ffed; color: #52c41a; }
        .status-impayee, .status-refuse { background-color: #fff2f0; color: #ff4d4f; }
        .status-paiement { background-color: #f0f9ff; color: #0369a1; }

        .dashboard-card { transition: all 0.3s ease; }
        .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }

        .activity-item { transition: all 0.3s ease; border-left: 3px solid transparent; }
        .activity-item:hover { border-left-color: #006233; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
        
        .dashboard-icon {
            width: 40px; height: 40px; display: flex; align-items: center; 
            justify-content: center; border-radius: 0.5rem; margin-bottom: 0.5rem;
        }
        
        .chart-container { position: relative; height: 300px; margin-bottom: 1.5rem; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                            <i class="fas fa-user text-primary text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800"><?= __('dashboard.welcome', ['name' => htmlspecialchars($user_name)]) ?></h1>
                            <p class="text-gray-600"><?= htmlspecialchars($user_email) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 border-t-4 border-primary">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                            <i class="fas fa-file-alt text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800"><?= __('dashboard.documents') ?></h3>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <a href="factures.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-blue-100 text-blue-600">
                                <i class="fas fa-file-invoice text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.invoices') ?></span>
                            <span class="font-bold"><?= $invoice_count ?></span>
                        </a>
                        <a href="devis.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-purple-100 text-purple-600">
                                <i class="fas fa-file-signature text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.quotes') ?></span>
                            <span class="font-bold"><?= $quote_count ?></span>
                        </a>
                        <a href="paiements.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-green-100 text-green-600">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.payments') ?></span>
                            <span class="font-bold"><?= $payment_count ?></span>
                        </a>
                    </div>
                </div>

                <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 border-t-4 border-secondary">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-secondary bg-opacity-10 flex items-center justify-center">
                            <i class="fas fa-address-book text-secondary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800"><?= __('dashboard.contacts') ?></h3>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <a href="clients.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.clients') ?></span>
                            <span class="font-bold"><?= $client_count ?></span>
                        </a>
                        <a href="prospects.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-yellow-100 text-yellow-600">
                                <i class="fas fa-handshake text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.prospects') ?></span>
                            <span class="font-bold"><?= $prospect_count ?></span>
                        </a>
                        <a href="responsables.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-red-100 text-red-600">
                                <i class="fas fa-user-shield text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.responsibles') ?></span>
                            <span class="font-bold"><?= $responsable_count ?></span>
                        </a>
                    </div>
                </div>
                
                <div class="dashboard-card bg-white rounded-xl shadow-sm p-6 border-t-4 border-accent">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-accent bg-opacity-10 flex items-center justify-center">
                            <i class="fas fa-cogs text-accent text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800"><?= __('dashboard.management') ?></h3>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="produits.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-blue-100 text-blue-600">
                                <i class="fas fa-boxes text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.products') ?></span>
                            <span class="font-bold"><?= $product_count ?></span>
                        </a>
                        <a href="livres_comptables.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-purple-100 text-purple-600">
                                <i class="fas fa-book text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.books') ?></span>
                            <span class="font-bold"><?= $book_count ?></span>
                        </a>
                        <a href="stock.php" class="flex flex-col items-center p-2 rounded-md hover:bg-gray-100 transition">
                            <div class="dashboard-icon bg-green-100 text-green-600">
                                <i class="fas fa-warehouse text-xl"></i>
                            </div>
                            <span class="text-xs"><?= __('dashboard.stock') ?></span>
                            <span class="font-bold"><?= $stock_count ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-primary mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800"><?= __('dashboard.revenue_evolution') ?></h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-primary mr-2"></div>
                            <span class="text-sm text-gray-600"><?= __('dashboard.revenue_dh') ?></span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-secondary mr-2"></div>
                            <span class="text-sm text-gray-600"><?= __('dashboard.operations') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="caChart"></canvas>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-gray-500 text-sm mb-1"><?= __('dashboard.total_revenue') ?></div>
                        <div class="text-xl font-bold text-primary">
                            <?= number_format(array_sum($chart_ca), 2, ',', ' ') ?> DH
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-gray-500 text-sm mb-1"><?= __('dashboard.operations') ?></div>
                        <div class="text-xl font-bold text-secondary">
                            <?= array_sum($chart_operations) ?>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-gray-500 text-sm mb-1"><?= __('dashboard.avg_operation') ?></div>
                        <div class="text-xl font-bold text-gray-800">
                            <?= array_sum($chart_operations) > 0 ? number_format(array_sum($chart_ca) / array_sum($chart_operations), 2, ',', ' ') . ' DH' : '0 DH' ?>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-gray-500 text-sm mb-1"><?= __('dashboard.period') ?></div>
                        <div class="text-xl font-bold text-gray-800">
                            <?= count($chart_months) ?> <?= __('dashboard.months') ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-secondary">
                <h2 class="text-xl font-bold text-gray-800 mb-6"><?= __('dashboard.recent_activity') ?></h2>
                
                <?php if (!empty($recent_activity)): ?>
                    <div class="space-y-4">
                    <?php foreach ($recent_activity as $item): 
                        $status_class = $item['type'] === 'paiement' ? 'status-paiement' : 'status-' . $item['statut'];
                        switch ($item['type']) {
                            case 'facture':
                                $type_icon = 'fa-file-invoice';
                                $type_color = 'text-primary';
                                $prefix = 'FAC-';
                                $date_label = __('dashboard.invoice_date');
                                $link = 'facture_view.php?id=' . $item['id'];
                                $type_label = __('dashboard.invoice');
                                break;
            
                            case 'devis':
                                $type_icon = 'fa-file-signature';
                                $type_color = 'text-secondary';
                                $prefix = 'DEV-';
                                $date_label = __('dashboard.quote_date');
                                $link = 'devis_view.php?id=' . $item['id'];
                                $type_label = __('dashboard.quote');
                                break;
            
                            case 'paiement':
                                $type_icon = 'fa-money-bill-wave';
                                $type_color = 'text-green-600';
                                $prefix = 'PAY-';
                                $date_label = __('dashboard.payment_date');
                                $link = 'paiement_view.php?id=' . $item['id'];
                                $type_label = __('dashboard.payment');
                                break;
                        }
                    ?>
                        <div class="activity-item group bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition">
                            <div class="flex items-start space-x-3">
                                <i class="fas <?= $type_icon ?> <?= $type_color ?> text-xl mt-1"></i>
                                <div class="flex-1">
                                    <div class="flex justify-between flex-wrap">
                                        <span class="font-bold text-gray-800">
                                            <?= $prefix . str_pad($item['id'], 5, '0', STR_PAD_LEFT) ?>
                                        </span>
                                        <span class="text-sm <?= $type_color ?>">
                                            <?= $type_label ?>
                                        </span>
                                    </div>
                                    
                                    <div class="text-gray-600 text-sm mt-1">
                                        <?= htmlspecialchars($item['client_nom']) ?>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-sm mt-2">
                                        <span class="text-gray-500">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?= $date_label ?> <?= date('d/m/Y', strtotime($item['date_emission'])) ?>
                                        </span>
                                        
                                        <?php if ($item['type'] === 'devis' && $item['date_validite']): ?>
                                        <span class="text-gray-500">
                                            <i class="far fa-clock mr-1"></i>
                                            <?= __('dashboard.valid_until') ?> <?= date('d/m/Y', strtotime($item['date_validite'])) ?>
                                        </span>
                                        <?php endif; ?>
                                        
                                        <span class="font-bold ml-auto">
                                            <?= number_format($item['montant_total'], 2, ',', ' ') ?> DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-sm px-3 py-1 rounded-full <?= $status_class ?>">
                                    <?= $item['type'] === 'paiement' ? __('dashboard.payment') : __("status." . $item['statut']) ?>
                                </span>
                                <a href="<?= $link ?>" class="text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-eye mr-1"></i> <?= __('dashboard.view') ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10 border border-dashed border-gray-300 rounded-lg">
                        <i class="fas fa-file text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-600 mb-2"><?= __('dashboard.no_activity') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    
    <script>
        const caCtx = document.getElementById('caChart').getContext('2d');
        
        const months = <?php echo json_encode($chart_months); ?>;
        const caData = <?php echo json_encode($chart_ca); ?>;
        const operationsData = <?php echo json_encode($chart_operations); ?>;
        
        const maxCA = Math.max(...caData);
        const maxOperations = Math.max(...operationsData);
        
        const caChart = new Chart(caCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: "<?= __('dashboard.revenue_dh') ?>",
                        data: caData,
                        borderColor: '#006233',
                        backgroundColor: 'rgba(0, 98, 51, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y',
                    },
                    {
                        label: "<?= __('dashboard.operations') ?>",
                        data: operationsData,
                        borderColor: '#C1272D',
                        backgroundColor: 'rgba(193, 39, 45, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1f2937',
                        bodyColor: '#1f2937',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    if (context.datasetIndex === 0) {
                                        label += new Intl.NumberFormat('fr-MA', { 
                                            style: 'currency', 
                                            currency: 'MAD',
                                            minimumFractionDigits: 2
                                        }).format(context.parsed.y);
                                    } else {
                                        label += context.parsed.y + ' <?= __('dashboard.operations') ?>';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: "<?= __('dashboard.revenue_dh') ?>", color: '#006233' },
                        min: 0,
                        max: maxCA * 1.2,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-MA', { 
                                    style: 'currency', 
                                    currency: 'MAD',
                                    minimumFractionDigits: 0
                                }).format(value);
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: "<?= __('dashboard.operations') ?>", color: '#C1272D' },
                        min: 0,
                        max: maxOperations * 1.2,
                        grid: { drawOnChartArea: false },
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>