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

try {
    $stmt_stock = $pdo->prepare("
        SELECT s.*, p.nom AS produit_nom, p.image_path, p.prix, p.categorie 
        FROM stock s
        JOIN produits p ON s.produit_id = p.id
        WHERE s.user_id = ?
        ORDER BY p.nom ASC
    ");
    $stmt_stock->execute([$user_id]);
    $stock_items = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);

    $stmt_products = $pdo->prepare("
        SELECT id, nom, prix 
        FROM produits 
        WHERE user_id = ?
        ORDER BY nom ASC
    ");
    $stmt_products->execute([$user_id]);
    $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $stock_items = [];
    $products = [];
    $error = __("stock.errors.db_error") . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('stock.title') ?> - efacture-maroc.com</title>
    
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
        .alert-low { background-color: #fff8e6; border-left: 4px solid #ffc53d; }
        .alert-critical { background-color: #fff1f0; border-left: 4px solid #ff4d4f; }
        
        .table-row:hover {
            background-color: #f8f9fa;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= __('stock.title') ?></h1>
                    <p class="text-gray-600"><?= __('stock.subtitle') ?></p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="document.getElementById('add-stock-modal').classList.remove('hidden')" 
                            class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-plus"></i>
                        <?= __('stock.add_button') ?>
                    </button>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.product') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.category') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.unit_price') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.quantity') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.alert_threshold') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.location') ?></th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('stock.table.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($stock_items)): ?>
                                <?php foreach ($stock_items as $item): 
                                    $alert_class = '';
                                    if ($item['quantite'] <= 0) {
                                        $alert_class = 'alert-critical';
                                    } elseif ($item['quantite'] <= $item['seuil_alerte']) {
                                        $alert_class = 'alert-low';
                                    }
                                ?>
                                    <tr class="table-row <?= $alert_class ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($item['produit_nom']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($item['categorie']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= number_format($item['prix'], 2, ',', ' ') ?> DH
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?= $item['quantite'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $item['seuil_alerte'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($item['emplacement']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="openEditModal(<?= $item['id'] ?>, <?= $item['produit_id'] ?>, <?= $item['quantite'] ?>, <?= $item['seuil_alerte'] ?>, '<?= htmlspecialchars($item['emplacement']) ?>')" 
                                                    class="text-primary hover:text-primary-dark mr-3">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="confirmDelete(<?= $item['id'] ?>)" 
                                                    class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        <?= __('stock.table.empty') ?>
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
    
    <div id="add-stock-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><?= __('stock.modal.add_title') ?></h3>
                <button onclick="document.getElementById('add-stock-modal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="includes/stock_action.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                
                <div class="mb-4">
                    <label for="produit_id" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.product_label') ?></label>
                    <select id="produit_id" name="produit_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value=""><?= __('stock.modal.product_select') ?></option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['nom']) ?> (<?= number_format($product['prix'], 2, ',', ' ') ?> DH)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.quantity_label') ?></label>
                    <input type="number" id="quantite" name="quantite" min="1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="mb-4">
                    <label for="seuil_alerte" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.threshold_label') ?></label>
                    <input type="number" id="seuil_alerte" name="seuil_alerte" min="0" value="10" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="mb-4">
                    <label for="emplacement" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.location_label') ?></label>
                    <input type="text" id="emplacement" name="emplacement" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('add-stock-modal').classList.add('hidden')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <?= __('stock.modal.cancel') ?>
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md">
                        <?= __('stock.modal.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="edit-stock-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800"><?= __('stock.modal.edit_title') ?></h3>
                <button onclick="document.getElementById('edit-stock-modal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="includes/stock_action.php" method="POST" class="p-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="mb-4">
                    <label for="edit_produit_id" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.product_label') ?></label>
                    <select id="edit_produit_id" name="produit_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" disabled>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['nom']) ?> (<?= number_format($product['prix'], 2, ',', ' ') ?> DH)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="edit_quantite" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.quantity_label') ?></label>
                    <input type="number" id="edit_quantite" name="quantite" min="0" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="mb-4">
                    <label for="edit_seuil_alerte" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.threshold_label') ?></label>
                    <input type="number" id="edit_seuil_alerte" name="seuil_alerte" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="mb-4">
                    <label for="edit_emplacement" class="block text-sm font-medium text-gray-700 mb-1"><?= __('stock.modal.location_label') ?></label>
                    <input type="text" id="edit_emplacement" name="emplacement" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('edit-stock-modal').classList.add('hidden')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <?= __('stock.modal.cancel') ?>
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md">
                        <?= __('stock.modal.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openEditModal(id, produit_id, quantite, seuil_alerte, emplacement) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_produit_id').value = produit_id;
            document.getElementById('edit_quantite').value = quantite;
            document.getElementById('edit_seuil_alerte').value = seuil_alerte;
            document.getElementById('edit_emplacement').value = emplacement;
            document.getElementById('edit-stock-modal').classList.remove('hidden');
        }
        
        function confirmDelete(id) {
            if (confirm('<?= __('stock.delete_confirm') ?>')) {
                window.location.href = 'includes/stock_action.php?action=delete&id=' + id;
            }
        }
    </script>
</body>
</html>