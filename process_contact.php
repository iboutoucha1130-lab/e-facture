<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/helpers.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name)) {
        $errors['name'] = __('process_contact.errors.name_required');
    }

    if (empty($email)) {
        $errors['email'] = __('process_contact.errors.email_required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __('process_contact.errors.email_invalid');
    }

    if (empty($message)) {
        $errors['message'] = __('process_contact.errors.message_required');
    }

    if (empty($errors)) {
        $success = true;
        $name = $email = $phone = $subject = $message = '';
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('process_contact.title') ?> - efacture-maroc.com</title>
    
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
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-8">
                    <?php if ($success): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold"><?= __('process_contact.success.title') ?></strong>
                            <span class="block sm:inline"><?= __('process_contact.success.message') ?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                        
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= __('process_contact.success.thank_you') ?></h2>
                            <p class="text-gray-600 mb-6"><?= __('process_contact.success.follow_up') ?></p>
                            <a href="contact.php" class="inline-block bg-primary hover:bg-green-800 text-white font-medium py-2 px-6 rounded-md transition duration-300">
                                <?= __('process_contact.success.back_button') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= __('process_contact.processing.title') ?></h2>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                                <strong class="font-bold"><?= __('process_contact.errors.title') ?></strong>
                                <span class="block sm:inline"><?= __('process_contact.errors.subtitle') ?></span>
                                <ul class="mt-2 list-disc list-inside">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-gray-600"><?= __('process_contact.errors.description') ?></p>
                            </div>
                            
                            <a href="contact.php" class="inline-block bg-primary hover:bg-green-800 text-white font-medium py-2 px-6 rounded-md transition duration-300">
                                <?= __('process_contact.errors.back_button') ?>
                            </a>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-primary mx-auto mb-4"></div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= __('process_contact.processing.message') ?></h2>
                                <p class="text-gray-600"><?= __('process_contact.processing.subtitle') ?></p>
                            </div>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    setTimeout(function() {
                                        document.querySelector('form').submit();
                                    }, 2000);
                                });
                            </script>
                            
                            <form action="process_contact.php" method="POST" class="hidden">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                                <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
                                <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                                <input type="hidden" name="message" value="<?= htmlspecialchars($message) ?>">
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>