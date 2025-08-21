<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once 'includes/config.php';
require_once 'includes/db_connection.php';
require_once 'includes/helpers.php';

$user_id = $_SESSION['user']['id'];
$responsable_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];
$success = false;

try {
    $stmt = $pdo->prepare("SELECT * FROM responsables WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $responsable_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $responsable = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$responsable) {
        header('Location: responsables.php');
        exit();
    }
} catch (PDOException $e) {
    $errors['general'] = __("responsable_edit.db_error") . $e->getMessage();
}

$permissions_list = [
    'factures' => __('responsable_edit.permissions.invoices'),
    'devis' => __('responsable_edit.permissions.quotes'),
    'clients' => __('responsable_edit.permissions.clients'),
    'produits' => __('responsable_edit.permissions.products'),
    'paiements' => __('responsable_edit.permissions.payments'),
    'rapports' => __('responsable_edit.permissions.reports')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_data = [
        'nom' => trim($_POST['nom']),
        'email' => trim($_POST['email']),
        'role' => trim($_POST['role']),
        'permissions' => isset($_POST['permissions']) ? $_POST['permissions'] : []
    ];

    if (empty($update_data['nom'])) {
        $errors['nom'] = __("responsable_edit.errors.name_required");
    }
    
    if (empty($update_data['email'])) {
        $errors['email'] = __("responsable_edit.errors.email_required");
    } elseif (!filter_var($update_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __("responsable_edit.errors.email_invalid");
    }
    
    if (empty($update_data['role'])) {
        $errors['role'] = __("responsable_edit.errors.role_required");
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE responsables SET
                    nom = :nom,
                    email = :email,
                    role = :role,
                    permissions = :permissions
                WHERE id = :id AND user_id = :user_id
            ");
            
            $params = [
                ':nom' => $update_data['nom'],
                ':email' => $update_data['email'],
                ':role' => $update_data['role'],
                ':permissions' => json_encode($update_data['permissions']),
                ':id' => $responsable_id,
                ':user_id' => $user_id
            ];
            
            $stmt->execute($params);
            
            $success = true;
            header("Location: responsables.php?success=" . urlencode(__("responsable_edit.update_success")));
            exit();
            
        } catch (PDOException $e) {
            $errors['general'] = __("responsable_edit.update_error") . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('responsable_edit.title') ?> - efacture-maroc.com</title>
    
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
        .form-input:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
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
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-user-shield text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('responsable_edit.title') ?></h1>
            </div>
            
            <?php if (!empty($errors['general'])): ?>
                <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle h-5 w-5 text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800"><?= $errors['general'] ?></h3>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <form method="post" class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><?= __('responsable_edit.general_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_edit.fullname') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" required
                                   value="<?= htmlspecialchars($responsable['nom']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['nom'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['nom'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_edit.email') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   value="<?= htmlspecialchars($responsable['email']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['email'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['email'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_edit.role') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="role" name="role" required
                                   value="<?= htmlspecialchars($responsable['role']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['role'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['role'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4"><?= __('responsable_edit.permissions_title') ?></h2>
                    
                    <?php 
                    $current_perms = json_decode($responsable['permissions'], true);
                    if (!is_array($current_perms)) $current_perms = [];
                    ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($permissions_list as $key => $label): ?>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="perm-<?= $key ?>" name="permissions[]" type="checkbox" 
                                           value="<?= $key ?>" 
                                           <?= in_array($key, $current_perms) ? 'checked' : '' ?>
                                           class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="perm-<?= $key ?>" class="font-medium text-gray-700"><?= $label ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="responsables.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('responsable_edit.cancel_button') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('responsable_edit.update_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>