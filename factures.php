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
$error = '';
$success = '';

try {
    $stmt = $pdo->prepare("
        SELECT factures.*, clients.nom AS client_nom 
        FROM factures 
        JOIN clients ON factures.client_id = clients.id 
        WHERE factures.user_id = :user_id 
        ORDER BY factures.date_facture DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = __('factures.error_retrieving') . $e->getMessage();
}

if (isset($_GET['delete'])) {
    $facture_id = (int)$_GET['delete'];
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("DELETE FROM facture_lignes WHERE facture_id = :facture_id");
        $stmt->bindParam(':facture_id', $facture_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $pdo->prepare("DELETE FROM factures WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $facture_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $pdo->commit();
            $success = __('factures.delete_success');
            header("Location: factures.php?success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = __('factures.delete_error') . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('factures.title') ?> - efacture-maroc.com</title>
    
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
    
    <style>
        .status-brouillon { background-color: #f0f0f0; color: #666; }
        .status-envoyee { background-color: #e6f7ff; color: #1890ff; }
        .status-payee { background-color: #f6ffed; color: #52c41a; }
        .status-impayee { background-color: #fff2f0; color: #ff4d4f; }
        
        .factures-table {
            min-width: 100%;
        }
        .factures-table th {
            background-color: #006233;
            color: white;
        }
        .factures-table tr:hover {
            background-color: #f8fafc;
        }
        
        .animate-pulse {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-900"><?= __('factures.title') ?></h1>
                <a href="facture_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                    <i class="fas fa-plus mr-2"></i> <?= __('factures.new_button') ?>
                </a>
            </div>
            
            <?php if ($error): ?>
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
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="rounded-md bg-green-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800"><?= htmlspecialchars($_GET['success']) ?></h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
                <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1"><?= __('factures.filter.status') ?></label>
                        <select id="status" name="status" class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            <option value=""><?= __('factures.filter.all') ?></option>
                            <option value="brouillon"><?= __('factures.status.draft') ?></option>
                            <option value="envoyee"><?= __('factures.status.sent') ?></option>
                            <option value="payee"><?= __('factures.status.paid') ?></option>
                            <option value="impayee"><?= __('factures.status.unpaid') ?></option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="client" class="block text-sm font-medium text-gray-700 mb-1"><?= __('factures.filter.client') ?></label>
                        <select id="client" name="client" class="form-select block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                            <option value=""><?= __('factures.filter.all_clients') ?></option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 mb-1"><?= __('factures.filter.from') ?></label>
                        <input type="date" id="from" name="from" class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 mb-1"><?= __('factures.filter.to') ?></label>
                        <input type="date" id="to" name="to" class="form-input block w-full rounded-md border-gray-300 py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                            <i class="fas fa-filter mr-2"></i> <?= __('factures.filter.button') ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if (empty($factures)): ?>
                <div class="text-center bg-white rounded-lg shadow-sm p-12 border-2 border-dashed border-gray-300">
                    <i class="fas fa-file-invoice text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2"><?= __('factures.empty_title') ?></h3>
                    <p class="text-gray-500 mb-6"><?= __('factures.empty_subtitle') ?></p>
                    <a href="facture_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-plus mr-2"></i> <?= __('factures.new_button') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="factures-table divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.invoice_number') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.date') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.client') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.amount') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.status') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('factures.table.due_date') ?></th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider"><?= __('factures.table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($factures as $facture): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        FAC-<?= str_pad($facture['id'], 5, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($facture['date_facture'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($facture['client_nom']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        <?= number_format($facture['montant_ht'] * (1 + $facture['taux_tva']/100), 2, ',', ' ') ?> DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 py-1 rounded-full text-xs <?= 'status-'.$facture['statut'] ?>">
                                            <?= __('factures.status.'.$facture['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="facture_view.php?id=<?= $facture['id'] ?>" class="text-primary hover:text-green-800 transition">
                                                <i class="fas fa-eye mr-1"></i> <?= __('factures.action.view') ?>
                                            </a>
                                            <a href="facture_edit.php?id=<?= $facture['id'] ?>" class="text-yellow-600 hover:text-yellow-800 transition">
                                                <i class="fas fa-edit mr-1"></i> <?= __('factures.action.edit') ?>
                                            </a>
                                            <a href="factures.php?delete=<?= $facture['id'] ?>" 
                                               class="text-secondary hover:text-red-800 transition"
                                               onclick="return confirm('<?= __('factures.delete_confirm') ?>')">
                                                <i class="fas fa-trash-alt mr-1"></i> <?= __('factures.action.delete') ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        document.querySelectorAll('a[href^="facture_"], a[href*="delete"]').forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.classList.add('transform', 'scale-105');
            });
            
            link.addEventListener('mouseleave', () => {
                link.classList.remove('transform', 'scale-105');
            });
        });
        
        const filterBtn = document.querySelector('button[type="submit"]');
        if (filterBtn) {
            filterBtn.addEventListener('mouseenter', () => {
                filterBtn.classList.add('animate-pulse');
            });
            
            filterBtn.addEventListener('mouseleave', () => {
                filterBtn.classList.remove('animate-pulse');
            });
        }
    </script>
</body>
</html>