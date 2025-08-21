<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$errors = [];
$success = false;
$name = '';
$email = '';
$company = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $company = trim($_POST['company']);

    if (empty($name)) {
        $errors['name'] = __('register.form.name_error.required');
    }
    
    if (empty($email)) {
        $errors['email'] = __('register.form.email_error.required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __('register.form.email_error.invalid');
    }
    
    if (empty($password)) {
        $errors['password'] = __('register.form.password_error.required');
    } elseif (strlen($password) < 6) {
        $errors['password'] = __('register.form.password_error.length');
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = __('register.form.confirm_password_error');
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $errors['email'] = __('register.form.email_error.used');
            }
        } catch (PDOException $e) {
            $errors['general'] = __('register.general_error');
        }
    }

    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, entreprise) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $company]);
            
            $success = true;
            $name = $email = $company = '';
            
        } catch (PDOException $e) {
            $errors['general'] = __('register.account_creation_error') . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('register.title') ?> - efacture-maroc.com</title>
    
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
        .register-bg {
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

    <main class="flex-grow register-bg py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900"><?= __('register.heading') ?></h2>
                <p class="mt-2 text-sm text-gray-600">
                    <?= __('register.subtitle') ?>
                </p>
            </div>
            
            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <?= __('register.success_message') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors['general'])): ?>
                <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?= $errors['general'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <form class="space-y-6" action="register.php" method="post">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <?= __('register.form.name') ?> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="<?= htmlspecialchars($name) ?>"
                            class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="<?= __('register.form.name_placeholder') ?>"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['name'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <?= __('register.form.email') ?> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="<?= htmlspecialchars($email) ?>"
                            class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="<?= __('register.form.email_placeholder') ?>"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
                        <?= __('register.form.company') ?>
                    </label>
                    <div class="relative">
                        <input
                            id="company"
                            name="company"
                            type="text"
                            value="<?= htmlspecialchars($company) ?>"
                            class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="<?= __('register.form.company_placeholder') ?>"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <?= __('register.form.password') ?> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="<?= __('register.form.password_placeholder') ?>"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                        <?= __('register.form.confirm_password') ?> <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            id="confirm_password"
                            name="confirm_password"
                            type="password"
                            required
                            class="input-focus block w-full rounded-md border-gray-300 pl-3 pr-10 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                            placeholder="<?= __('register.form.confirm_password_placeholder') ?>"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['confirm_password'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-secondary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-white group-hover:text-red-200"></i>
                        </span>
                        <?= __('register.form.submit') ?>
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    <?= __('register.form.login_link') ?>
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    <?= __('register.form.required_fields') ?>
                </p>
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
            const nameField = document.getElementById('name');
            if (nameField) {
                nameField.focus();
            }
        });
    </script>
</body>
</html>