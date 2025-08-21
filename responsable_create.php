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
$errors = [];
$success = false;

$responsable_data = [
    'nom' => '',
    'email' => '',
    'role' => '',
    'permissions' => [],
    'statut' => 'actif'
];

$available_permissions = [
    'factures' => __('responsable_create.permissions.invoices'),
    'devis' => __('responsable_create.permissions.quotes'),
    'clients' => __('responsable_create.permissions.clients'),
    'produits' => __('responsable_create.permissions.products'),
    'paiements' => __('responsable_create.permissions.payments'),
    'rapports' => __('responsable_create.permissions.reports')
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responsable_data = [
        'nom' => trim($_POST['nom']),
        'email' => trim($_POST['email']),
        'role' => trim($_POST['role']),
        'permissions' => isset($_POST['permissions']) ? $_POST['permissions'] : [],
        'statut' => $_POST['statut']
    ];

    if (empty($responsable_data['nom'])) {
        $errors['nom'] = __('responsable_create.errors.name_required');
    }

    if (empty($responsable_data['email'])) {
        $errors['email'] = __('responsable_create.errors.email_required');
    } elseif (!filter_var($responsable_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __('responsable_create.errors.email_invalid');
    }

    if (empty($responsable_data['role'])) {
        $errors['role'] = __('responsable_create.errors.role_required');
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO responsables (
                    user_id, nom, email, role, permissions, statut
                ) VALUES (
                    :user_id, :nom, :email, :role, :permissions, :statut
                )
            ");
            
            $params = [
                ':user_id' => $user_id,
                ':nom' => $responsable_data['nom'],
                ':email' => $responsable_data['email'],
                ':role' => $responsable_data['role'],
                ':permissions' => json_encode($responsable_data['permissions']),
                ':statut' => $responsable_data['statut']
            ];
            
            $stmt->execute($params);
            $success = true;
            
            header("Location: responsables.php?success=" . urlencode(__('responsable_create.success')));
            exit();
            
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors['email'] = __('responsable_create.errors.email_exists');
            } else {
                $errors['general'] = __('responsable_create.errors.general') . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('responsable_create.title') ?> - efacture-maroc.com</title>
    
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
        .form-input:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
        
        .status-active { background-color: #f6ffed; color: #52c41a; }
        .status-inactive { background-color: #fff2f0; color: #ff4d4f; }
        
        .permission-item {
            transition: all 0.2s ease;
        }
        .permission-item:hover {
            transform: translateY(-2px);
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
                <h1 class="text-2xl font-bold text-gray-900"><?= __('responsable_create.title') ?></h1>
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
                    <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200"><?= __('responsable_create.personal_info') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_create.fullname') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" required
                                   value="<?= htmlspecialchars($responsable_data['nom']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-3 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['nom'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['nom'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_create.email') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   value="<?= htmlspecialchars($responsable_data['email']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-3 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['email'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['email'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_create.role') ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="role" name="role" required
                                   value="<?= htmlspecialchars($responsable_data['role']) ?>"
                                   class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-3 focus:outline-none sm:text-sm">
                            <?php if (isset($errors['role'])): ?>
                                <p class="mt-2 text-sm text-red-600"><?= $errors['role'] ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= __('responsable_create.status') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="statut" name="statut" required
                                class="form-input block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 focus:outline-none sm:text-sm">
                                <option value="actif" <?= ($responsable_data['statut'] == 'actif') ? 'selected' : '' ?>><?= __('responsable_create.active') ?></option>
                                <option value="inactif" <?= ($responsable_data['statut'] == 'inactif') ? 'selected' : '' ?>><?= __('responsable_create.inactive') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="mb-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200"><?= __('responsable_create.permissions_title') ?></h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($available_permissions as $key => $label): ?>
                            <div class="permission-item flex items-center p-3 border border-gray-200 rounded-lg hover:border-primary transition">
                                <input type="checkbox" id="perm_<?= $key ?>" name="permissions[]" value="<?= $key ?>"
                                    <?= in_array($key, $responsable_data['permissions']) ? 'checked' : '' ?>
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="perm_<?= $key ?>" class="ml-3 block text-sm font-medium text-gray-700">
                                    <?= $label ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="responsables.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('responsable_create.cancel') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-save mr-2"></i> <?= __('responsable_create.save') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>