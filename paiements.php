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
        SELECT p.*, f.id AS facture_id, c.nom AS client_nom 
        FROM paiements p
        JOIN factures f ON p.facture_id = f.id
        JOIN clients c ON f.client_id = c.id
        WHERE p.user_id = :user_id 
        ORDER BY p.date_paiement DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = __('paiements.error_retrieving') . $e->getMessage();
}

if (isset($_GET['delete'])) {
    $paiement_id = (int)$_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM paiements WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $paiement_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $success = __('paiements.delete_success');
            header("Location: paiements.php?success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $error = __('paiements.delete_error') . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('paiements.title') ?> - efacture-maroc.com</title>
    
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
        .paiements-table {
            min-width: 100%;
        }
        .paiements-table th {
            background-color: #006233;
            color: white;
        }
        .paiements-table tr:hover {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-900"><?= __('paiements.title') ?></h1>
                <a href="paiement_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                    <i class="fas fa-plus mr-2"></i> <?= __('paiements.add_button') ?>
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
            
            <?php if (empty($paiements)): ?>
                <div class="text-center bg-white rounded-lg shadow-sm p-12 border-2 border-dashed border-gray-300">
                    <i class="fas fa-money-bill-wave text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2"><?= __('paiements.empty_title') ?></h3>
                    <p class="text-gray-500 mb-6"><?= __('paiements.empty_subtitle') ?></p>
                    <a href="paiement_create.php" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-green-800 text-white rounded-md shadow-sm font-medium transition">
                        <i class="fas fa-plus mr-2"></i> <?= __('paiements.add_button') ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="paiements-table divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.date') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.invoice') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.client') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.amount') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.method') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.reference') ?></th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider"><?= __('paiements.table.actions') ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($paiements as $paiement): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($paiement['date_paiement'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        FAC-<?= str_pad($paiement['facture_id'], 5, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($paiement['client_nom']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= number_format($paiement['montant'], 2, ',', ' ') ?> DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($paiement['mode_paiement']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($paiement['reference']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="paiement_view.php?id=<?= $paiement['id'] ?>" class="text-primary hover:text-green-800 transition">
                                                <i class="fas fa-eye mr-1"></i> <?= __('paiements.view_button') ?>
                                            </a>
                                            <a href="paiement_edit.php?id=<?= $paiement['id'] ?>" class="text-yellow-600 hover:text-yellow-800 transition">
                                                <i class="fas fa-edit mr-1"></i> <?= __('paiements.edit_button') ?>
                                            </a>
                                            <a href="paiements.php?delete=<?= $paiement['id'] ?>" 
                                               class="text-secondary hover:text-red-800 transition"
                                               onclick="return confirm('<?= __('paiements.delete_confirm') ?>')">
                                                <i class="fas fa-trash-alt mr-1"></i> <?= __('paiements.delete_button') ?>
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
        document.querySelectorAll('a[href^="paiement_"], a[href*="delete"]').forEach(link => {
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