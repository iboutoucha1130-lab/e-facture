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
$translations = require __DIR__ . "/lang/$lang.php";

$success_message = '';
$error_message = '';

$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $entreprise = trim($_POST['entreprise']);
    $adresse = trim($_POST['adresse']);
    
    if (empty($nom)) {
        $error_message = "error.required_field";
    } else {
        try {
            $update_stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, telephone = ?, entreprise = ?, adresse = ? WHERE id = ?");
            $update_stmt->execute([$nom, $email, $telephone, $entreprise, $adresse, $user_id]);
            
            $_SESSION['user']['nom'] = $nom;
            $_SESSION['user']['email'] = $email;
            
            $success_message = "success.profile_update";
            
            $stmt->execute([$user_id]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error_message = "error.database";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('profile.edit_title') ?> - efacture-maroc.com</title>
    
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center mb-8">
                <div class="bg-primary bg-opacity-10 p-3 rounded-lg mr-4">
                    <i class="fas fa-user-edit text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('profile.edit_title') ?></h1>
            </div>

            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="p-6">
                    <?php if ($success_message): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <?= __($success_message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <?= __($error_message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">
                                    <?= __('profile.fullname') ?> *
                                </label>
                                <input 
                                    type="text" 
                                    id="nom" 
                                    name="nom" 
                                    value="<?= htmlspecialchars($user_data['nom'] ?? '') ?>" 
                                    class="form-input block w-full rounded-md border-gray-300 py-2 px-3"
                                    required
                                >
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    <?= __('profile.email') ?> *
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" 
                                    class="form-input block w-full rounded-md border-gray-300 py-2 px-3"
                                    required
                                >
                            </div>
                            
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">
                                    <?= __('profile.phone') ?>
                                </label>
                                <input 
                                    type="tel" 
                                    id="telephone" 
                                    name="telephone" 
                                    value="<?= htmlspecialchars($user_data['telephone'] ?? '') ?>" 
                                    class="form-input block w-full rounded-md border-gray-300 py-2 px-3"
                                >
                            </div>
                            
                            <div>
                                <label for="entreprise" class="block text-sm font-medium text-gray-700 mb-1">
                                    <?= __('profile.company') ?>
                                </label>
                                <input 
                                    type="text" 
                                    id="entreprise" 
                                    name="entreprise" 
                                    value="<?= htmlspecialchars($user_data['entreprise'] ?? '') ?>" 
                                    class="form-input block w-full rounded-md border-gray-300 py-2 px-3"
                                >
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">
                                    <?= __('profile.address') ?>
                                </label>
                                <textarea 
                                    id="adresse" 
                                    name="adresse" 
                                    class="form-input block w-full rounded-md border-gray-300 py-2 px-3"
                                ><?= htmlspecialchars($user_data['adresse'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="flex justify-between pt-4">
                            <a 
                                href="parametres.php?tab=profile" 
                                class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-md font-medium"
                            >
                                <i class="fas fa-arrow-left mr-2"></i><?= __('profile.cancel_button') ?>
                            </a>
                            <button 
                                type="submit" 
                                name="update_profile"
                                class="bg-primary hover:bg-green-800 text-white py-2 px-6 rounded-md font-medium"
                            >
                                <i class="fas fa-save mr-2"></i><?= __('profile.update_button') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>