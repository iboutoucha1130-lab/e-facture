<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$error = '';
$success = '';
$step = 1;
$email = '';
$new_password = '';
$confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_identity'])) {
        $email = trim($_POST['email']);
        $nom = trim($_POST['nom']);
        $entreprise = trim($_POST['entreprise']);

        if (empty($email) || empty($nom) || empty($entreprise)) {
            $error = __("forgot_password.error.required_fields");
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email AND nom = :nom AND entreprise = :entreprise");
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':entreprise', $entreprise, PDO::PARAM_STR);
                $stmt->execute();
                
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    $_SESSION['reset_user_id'] = $user['id'];
                    $step = 2;
                } else {
                    $error = __("forgot_password.error.no_account");
                }
            } catch (PDOException $e) {
                $error = __("forgot_password.error.technical");
            }
        }
    }

    if (isset($_POST['reset_password']) && isset($_SESSION['reset_user_id'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($new_password) || empty($confirm_password)) {
            $error = __("forgot_password.error.required_fields");
        } elseif ($new_password !== $confirm_password) {
            $error = __("forgot_password.error.password_mismatch");
        } else {
            try {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE utilisateurs SET password = :password WHERE id = :id");
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $_SESSION['reset_user_id'], PDO::PARAM_INT);
                $stmt->execute();

                unset($_SESSION['reset_user_id']);
                $success = __("forgot_password.success.reset_success");
                $step = 3;
            } catch (PDOException $e) {
                $error = __("forgot_password.error.technical");
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
    <title><?= __('forgot_password.title') ?> - efacture-maroc.com</title>
    
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
        .login-bg {
            background: linear-gradient(135deg, rgba(0, 98, 51, 0.05) 0%, rgba(255, 255, 255, 0) 50%);
        }
        .input-focus:focus {
            border-color: #006233;
            box-shadow: 0 0 0 3px rgba(0, 98, 51, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow login-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    <?= $step === 3 ? __('forgot_password.step3.title') : ($step === 2 ? __('forgot_password.step2.title') : __('forgot_password.step1.title')) ?>
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    <?= $step === 3 ? __('forgot_password.step3.subtitle') : ($step === 2 ? __('forgot_password.step2.subtitle') : __('forgot_password.step1.subtitle')) ?>
                </p>
            </div>
            
            <?php if ($success): ?>
                <div class="rounded-md bg-green-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800"><?= $success ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="rounded-md bg-red-50 p-4">
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
            
            <?php if ($step === 1): ?>
            <form class="mt-8 space-y-6" action="forgot_password.php" method="post">
                <div class="space-y-4 rounded-md shadow-sm">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?= __('forgot_password.email') ?></label>
                        <div class="relative">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value="<?= htmlspecialchars($email) ?>"
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('forgot_password.placeholder.email') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1"><?= __('forgot_password.fullname') ?></label>
                        <div class="relative">
                            <input
                                id="nom"
                                name="nom"
                                type="text"
                                required
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('forgot_password.placeholder.fullname') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="entreprise" class="block text-sm font-medium text-gray-700 mb-1"><?= __('forgot_password.company') ?></label>
                        <div class="relative">
                            <input
                                id="entreprise"
                                name="entreprise"
                                type="text"
                                required
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('forgot_password.placeholder.company') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-building text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" name="verify_identity" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-check-circle text-white group-hover:text-red-200"></i>
                        </span>
                        <?= __('forgot_password.verify_button') ?>
                    </button>
                </div>
            </form>
            <?php elseif ($step === 2): ?>
            <form class="mt-8 space-y-6" action="forgot_password.php" method="post">
                <div class="space-y-4 rounded-md shadow-sm">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1"><?= __('forgot_password.new_password') ?></label>
                        <div class="relative">
                            <input
                                id="new_password"
                                name="new_password"
                                type="password"
                                required
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('forgot_password.placeholder.new_password') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1"><?= __('forgot_password.confirm_password') ?></label>
                        <div class="relative">
                            <input
                                id="confirm_password"
                                name="confirm_password"
                                type="password"
                                required
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('forgot_password.placeholder.confirm_password') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" name="reset_password" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sync-alt text-white group-hover:text-red-200"></i>
                        </span>
                        <?= __('forgot_password.reset_button') ?>
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div class="text-center">
                <a href="login.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i> <?= __('forgot_password.login_button') ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('mouseenter', () => {
                    submitBtn.classList.add('transform', 'scale-105');
                });
                
                submitBtn.addEventListener('mouseleave', () => {
                    submitBtn.classList.remove('transform', 'scale-105');
                });
            }
        });
    </script>
</body>
</html>