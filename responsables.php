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
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$error = '';
$success = '';

try {
    $query = "SELECT * FROM responsables WHERE user_id = ?";
    $params = [$user_id];
    
    if (!empty($search)) {
        $query .= " AND (nom LIKE ? OR email LIKE ? OR role LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    $query .= " ORDER BY id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM responsables WHERE user_id = ?");
    $stmt_count->execute([$user_id]);
    $total_responsables = $stmt_count->fetchColumn();

} catch (PDOException $e) {
    $responsables = [];
    $total_responsables = 0;
    $error = __("error.db_error") . $e->getMessage();
}

if (isset($_GET['delete'])) {
    $responsable_id = (int)$_GET['delete'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM responsables WHERE id = ? AND user_id = ?");
        $stmt->execute([$responsable_id, $user_id]);
        
        if ($stmt->rowCount() > 0) {
            $success = __("responsables.delete_success");
            header("Location: responsables.php?success=" . urlencode($success));
            exit();
        }
    } catch (PDOException $e) {
        $error = __("responsables.delete_error") . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('responsables.title') ?> - efacture-maroc.com</title>
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
        .status-active { background-color: #f6ffed; color: #52c41a; }
        .status-inactive { background-color: #fff2f0; color: #ff4d4f; }
        .table-row:hover { background-color: #f9f9f9; }
        .permission-item { transition: all 0.2s ease; }
        .permission-item:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800"><?= __('responsables.title') ?></h1>
                    <p class="text-gray-600"><?= __('responsables.subtitle') ?></p>
                </div>
                <a href="responsable_create.php" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fas fa-plus"></i> <?= __('responsables.add_button') ?>
                </a>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <form method="get" class="flex items-center gap-2 w-full">
                        <i class="fas fa-search text-gray-500"></i>
                        <input type="text" name="search" placeholder="<?= __('responsables.search_placeholder') ?>" value="<?= htmlspecialchars($search) ?>" class="border-0 focus:ring-0 w-full">
                        <button type="submit" class="bg-primary text-white px-3 py-1 rounded-md"><?= __('responsables.search_button') ?></button>
                    </form>
                    <span class="text-sm text-gray-600"><?= $total_responsables ?> <?= __('responsables.count_label') ?></span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('responsables.table.name') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('responsables.table.email') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('responsables.table.role') ?></th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('responsables.table.permissions') ?></th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('responsables.table.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($responsables)): ?>
                                <?php foreach ($responsables as $responsable): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary bg-opacity-10 flex items-center justify-center">
                                                    <i class="fas fa-user-shield text-primary"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($responsable['nom']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"><?= htmlspecialchars($responsable['email']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?= htmlspecialchars($responsable['role']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                <?php 
                                                    $perms = json_decode($responsable['permissions'], true);
                                                    echo implode(', ', array_slice($perms, 0, 3));
                                                    if (count($perms) > 3) echo '...';
                                                ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="responsable_edit.php?id=<?= $responsable['id'] ?>" class="text-primary hover:text-primary-dark mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="responsables.php?delete=<?= $responsable['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('<?= __('responsables.delete_confirm') ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <?= __('responsables.empty_message') ?>
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