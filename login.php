<?php
session_start();

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

if (isset($_GET['logout'])) {
    $success = __("login.logout_success");
}

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

if (empty($_SESSION['user']) && !empty($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    try {
        $stmt = $pdo->prepare("SELECT u.id, u.nom, u.email 
                             FROM utilisateurs u
                             JOIN remember_tokens rt ON u.id = rt.user_id
                             WHERE rt.token = :token AND rt.expires_at > NOW()");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['nom']
            ];
            header('Location: dashboard.php');
            exit();
        } else {
            setcookie('remember_token', '', time() - 3600, '/');
        }
    } catch (PDOException $e) {
        error_log("Remember me error: " . $e->getMessage());
    }
}

$email = '';
$error = '';
$remember = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember-me']);
    
    if (empty($email) || empty($password)) {
        $error = __("login.required_fields");
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, nom, email, password FROM utilisateurs WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['nom']
                    ];
                    
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + 30 * 24 * 3600;
                        
                        try {
                            $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = :user_id");
                            $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                            $stmt->execute();
                            
                            $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) 
                                                 VALUES (:user_id, :token, FROM_UNIXTIME(:expires))");
                            $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                            $stmt->bindParam(':expires', $expires, PDO::PARAM_INT);
                            $stmt->execute();
                            
                            setcookie('remember_token', $token, $expires, '/', '', true, true);
                        } catch (PDOException $e) {
                            error_log("Remember token error: " . $e->getMessage());
                        }
                    }
                    
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = __("login.invalid_credentials");
                }
            } else {
                $error = __("login.invalid_credentials");
            }
        } catch (PDOException $e) {
            error_log("Database error in login: " . $e->getMessage());
            $error = __("login.technical_error");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('login.title') ?> - efacture-maroc.com</title>
    
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
                <h2 class="mt-6 text-3xl font-bold text-gray-900"><?= __('login.heading') ?></h2>
                <p class="mt-2 text-sm text-gray-600">
                    <?= __('login.subheading') ?>
                </p>
            </div>
            
            <?php if (isset($success)): ?>
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
            
            <form class="mt-8 space-y-6" action="login.php" method="post">
                <div class="space-y-4 rounded-md shadow-sm">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?= __('login.email_label') ?></label>
                        <div class="relative">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value="<?= htmlspecialchars($email) ?>"
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('login.email_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1"><?= __('login.password_label') ?></label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                placeholder="<?= __('login.password_placeholder') ?>"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" <?= $remember ? 'checked' : '' ?>>
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900"><?= __('login.remember_me') ?></label>
                    </div>

                    <div class="text-sm">
                        <a href="forgot_password.php" class="font-medium text-primary hover:text-green-800"><?= __('login.forgot_password') ?></a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-white group-hover:text-red-200"></i>
                        </span>
                        <?= __('login.submit_button') ?>
                    </button>
                </div>
            </form>
            
            <div class="text-center text-sm">
                <span class="text-gray-600"><?= __('login.no_account') ?></span>
                <a href="register.php" class="font-medium text-primary hover:text-green-800"><?= __('login.register_link') ?></a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    
    <script>
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('mouseenter', () => {
                submitBtn.classList.add('transform', 'scale-105');
            });
            
            submitBtn.addEventListener('mouseleave', () => {
                submitBtn.classList.remove('transform', 'scale-105');
            });
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.focus();
            }
        });
    </script>
</body>
</html>