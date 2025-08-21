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
        SELECT devis.*, clients.nom AS client_nom 
        FROM devis 
        JOIN clients ON devis.client_id = clients.id 
        WHERE devis.user_id = :user_id 
        ORDER BY devis.date_creation DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $devis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = __('devis.error_retrieving') . $e->getMessage();
}

if (isset($_GET['delete'])) {
    $devis_id = (int)$_GET['delete'];
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("DELETE FROM devis_lignes WHERE devis_id = :devis_id");
        $stmt->bindParam(':devis_id', $devis_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $pdo->prepare("DELETE FROM devis WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $devis_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $pdo->commit();
            $success = __('devis.delete_success');
            header("Location: devis.php?success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = __('devis.delete_error') . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('devis.title') ?> - efacture-maroc.com</title>
    
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
        .status-accepte { background-color: #f6ffed; color: #52c41a; }
        .status-refuse { background-color: #fff2f0; color: #ff4d4f; }
        
        .devis-table {
            min-width: 100%;
        }
        .devis-table th {
            background-color: #006233;
            color: white;
        }
        .devis-table tr:hover {
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
                <h1 class="text-2xl font-bold text-gray-900"><?= __('devis.title') ?></h1>
                <a href="devis_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                    <i class="fas fa-plus mr-2"></i> <?= __('devis.add_button') ?>
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
            
            <?php if (empty($devis)): ?>
                <div class="text-center bg-white rounded-lg shadow-sm p-12 border-2 border-dashed border-gray-300">
                    <i class="fas fa-file-signature text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2"><?= __('devis.empty_title') ?></h3>
                    <p class="text-gray-500 mb-6"><?= __('devis.empty_subtitle') ?></p>
                    <a href="devis_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-plus mr-2"></i> <?= __('devis.add_button') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="devis-table divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('devis.table.number') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('devis.table.date') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('devis.table.client') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('devis.table.amount') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('devis.table.status') ?></th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider"><?= __('devis.table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($devis as $devis_item): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        DEV-<?= str_pad($devis_item['id'], 5, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($devis_item['date_creation'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($devis_item['client_nom']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        <?= number_format($devis_item['montant_ht'] * (1 + $devis_item['taux_tva']/100), 2, ',', ' ') ?> DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 py-1 rounded-full text-xs <?= 'status-'.$devis_item['statut'] ?>">
                                            <?= __('devis.status.'.$devis_item['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="devis_view.php?id=<?= $devis_item['id'] ?>" class="text-primary hover:text-green-800 transition">
                                                <i class="fas fa-eye mr-1"></i> <?= __('devis.view_button') ?>
                                            </a>
                                            <a href="devis_edit.php?id=<?= $devis_item['id'] ?>" class="text-yellow-600 hover:text-yellow-800 transition">
                                                <i class="fas fa-edit mr-1"></i> <?= __('devis.edit_button') ?>
                                            </a>
                                            <a href="facturer.php?id=<?= $devis_item['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-800 transition"
                                               onclick="return confirm('<?= __('devis.convert_confirm') ?>')">
                                                <i class="fas fa-file-invoice mr-1"></i> <?= __('devis.convert_button') ?>
                                            </a>
                                            <a href="devis.php?delete=<?= $devis_item['id'] ?>" 
                                               class="text-secondary hover:text-red-800 transition"
                                               onclick="return confirm('<?= __('devis.delete_confirm') ?>')">
                                                <i class="fas fa-trash-alt mr-1"></i> <?= __('devis.delete_button') ?>
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
        document.querySelectorAll('a[href^="devis_"], a[href*="delete"]').forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.classList.add('transform', 'scale-105');
            });
            
            link.addEventListener('mouseleave', () => {
                link.classList.remove('transform', 'scale-105');
            });
        });
        
        const newDevisBtn = document.querySelector('a[href="devis_create.php"]');
        if (newDevisBtn) {
            newDevisBtn.addEventListener('mouseenter', () => {
                newDevisBtn.classList.add('animate-pulse');
            });
            
            newDevisBtn.addEventListener('mouseleave', () => {
                newDevisBtn.classList.remove('animate-pulse');
            });
        }
    </script>
</body>
</html>