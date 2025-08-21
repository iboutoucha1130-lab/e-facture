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
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE user_id = :user_id ORDER BY nom ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = __('produits.error_retrieving') . $e->getMessage();
}

if (isset($_GET['delete'])) {
    $produit_id = (int)$_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $produit_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $success = __('produits.delete_success');
            header("Location: produits.php?success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $error = __('produits.delete_error') . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('produits.title') ?> - efacture-maroc.com</title>
    
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
        .produits-table {
            min-width: 100%;
        }
        .produits-table th {
            background-color: #006233;
            color: white;
        }
        .produits-table tr:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-900"><?= __('produits.title') ?></h1>
                <a href="produit_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                    <i class="fas fa-plus mr-2"></i> <?= __('produits.add_button') ?>
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
            
            <?php if (empty($produits)): ?>
                <div class="text-center bg-white rounded-lg shadow-sm p-12 border-2 border-dashed border-gray-300">
                    <i class="fas fa-box-open text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2"><?= __('produits.empty_title') ?></h3>
                    <p class="text-gray-500 mb-6"><?= __('produits.empty_subtitle') ?></p>
                    <a href="produit_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-plus mr-2"></i> <?= __('produits.add_button') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="produits-table divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('produits.table.name') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('produits.table.description') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('produits.table.category') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('produits.table.price') ?></th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider"><?= __('produits.table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($produit['nom']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($produit['description']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($produit['categorie']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= number_format($produit['prix'], 2, ',', ' ') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="produit_edit.php?id=<?= $produit['id'] ?>" class="text-primary hover:text-green-800 transition">
                                                <i class="fas fa-edit mr-1"></i> <?= __('produits.edit_button') ?>
                                            </a>
                                            <a href="produits.php?delete=<?= $produit['id'] ?>" 
                                               class="text-secondary hover:text-red-800 transition"
                                               onclick="return confirm('<?= __('produits.delete_confirm') ?>')">
                                                <i class="fas fa-trash-alt mr-1"></i> <?= __('produits.delete_button') ?>
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
        document.querySelectorAll('a[href^="produit_edit"], a[href*="delete"]').forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.classList.add('transform', 'scale-105');
            });
            
            link.addEventListener('mouseleave', () => {
                link.classList.remove('transform', 'scale-105');
            });
        });
    </script>
</body>
</html>