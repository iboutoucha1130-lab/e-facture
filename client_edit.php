<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$lang = $_SESSION['lang'] ?? 'fr';
$translations = require __DIR__ . '/lang/' . $lang . '.php';

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = false;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: clients.php');
    exit();
}

$client_id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $client_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $client_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$client_data) {
        header('Location: clients.php');
        exit();
    }
} catch (PDOException $e) {
    $errors['general'] = __("error.db_error") . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_data = [
        'id' => $client_id,
        'nom' => trim($_POST['nom']),
        'telephone' => trim($_POST['telephone']),
        'email' => trim($_POST['email']),
        'adresse' => trim($_POST['adresse']),
        'ice' => trim($_POST['ice']),
        'ville' => trim($_POST['ville']),
        'code_postal' => trim($_POST['code_postal'])
    ];

    if (empty($client_data['nom'])) {
        $errors['nom'] = __("client_edit.errors.name_required");
    }
    
    if (empty($client_data['ice'])) {
        $errors['ice'] = __("client_edit.errors.ice_required");
    } elseif (!preg_match('/^[0-9]{15}$/', $client_data['ice'])) {
        $errors['ice'] = __("client_edit.errors.ice_invalid");
    }
    
    if (!empty($client_data['email']) && !filter_var($client_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __("client_edit.errors.email_invalid");
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE clients SET 
                nom = :nom, 
                telephone = :telephone, 
                email = :email, 
                adresse = :adresse, 
                ice = :ice, 
                ville = :ville, 
                code_postal = :code_postal
                WHERE id = :id AND user_id = :user_id");
            
            $params = [
                ':nom' => $client_data['nom'],
                ':telephone' => $client_data['telephone'],
                ':email' => $client_data['email'],
                ':adresse' => $client_data['adresse'],
                ':ice' => $client_data['ice'],
                ':ville' => $client_data['ville'],
                ':code_postal' => $client_data['code_postal'],
                ':id' => $client_id,
                ':user_id' => $user_id
            ];
            
            if ($stmt->execute($params)) {
                $success = true;
            }
        } catch (PDOException $e) {
            $errors['general'] = __("error.db_error") . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('client_edit.title') ?> - efacture-maroc.com</title>
    
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
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-user-edit text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('client_edit.title') ?></h1>
            </div>
            
            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <?= __('client_edit.success_message') ?> 
                                <a href="clients.php" class="font-medium text-green-800 hover:text-green-700">
                                    <?= __('client_edit.back_to_list') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.name_label') ?> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="<?= htmlspecialchars($client_data['nom']) ?>"
                                required
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.name_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        <?php if (isset($errors['nom'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['nom'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="ice" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.ice_label') ?> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="ice"
                                name="ice"
                                value="<?= htmlspecialchars($client_data['ice']) ?>"
                                required
                                maxlength="15"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.ice_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                        </div>
                        <?php if (isset($errors['ice'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['ice'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.phone_label') ?>
                        </label>
                        <div class="relative">
                            <input
                                type="tel"
                                id="telephone"
                                name="telephone"
                                value="<?= htmlspecialchars($client_data['telephone']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.phone_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-phone text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.email_label') ?>
                        </label>
                        <div class="relative">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($client_data['email']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.email_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.address_label') ?>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="adresse"
                                name="adresse"
                                value="<?= htmlspecialchars($client_data['adresse']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.address_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.city_label') ?>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                value="<?= htmlspecialchars($client_data['ville']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.city_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-city text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= __('client_edit.form.postal_label') ?>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="code_postal"
                                name="code_postal"
                                value="<?= htmlspecialchars($client_data['code_postal']) ?>"
                                class="form-input block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 placeholder-gray-500 focus:outline-none sm:text-sm"
                                placeholder="<?= __('client_edit.form.postal_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-mail-bulk text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="clients.php" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fas fa-times mr-2"></i> <?= __('client_edit.cancel_button') ?>
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition animate-pulse">
                        <i class="fas fa-save mr-2"></i> <?= __('client_edit.save_button') ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        const iceInput = document.getElementById('ice');
        if (iceInput) {
            iceInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
        
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('mouseenter', () => {
                submitBtn.classList.add('animate-pulse');
            });
            
            submitBtn.addEventListener('mouseleave', () => {
                submitBtn.classList.remove('animate-pulse');
            });
        }
    </script>
</body>
</html>