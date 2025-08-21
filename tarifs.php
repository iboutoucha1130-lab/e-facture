<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$lang = $_SESSION['lang'] ?? 'fr';
$translations = require __DIR__ . "/lang/$lang.php";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('tarifs.title') ?> - efacture-maroc.com</title>
    
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
        .pricing-card {
            transition: all 0.3s ease;
            perspective: 1000px;
        }
        .pricing-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
        }
        .popular-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            transform: rotate(5deg);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4"><?= __('tarifs.title') ?></h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?= __('tarifs.subtitle') ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="pricing-card bg-white rounded-xl shadow-md p-8 relative border-2 border-gray-100">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= __('tarifs.basic.name') ?></h3>
                        <div class="text-4xl font-bold text-primary mb-1">
                            <?= __('tarifs.basic.price') ?>
                        </div>
                        <p class="text-gray-500"><?= __('tarifs.basic.period') ?></p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.invoices') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.clients') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.support') ?>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-times-circle mr-3"></i>
                            <?= __('tarifs.features.storage') ?>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-times-circle mr-3"></i>
                            <?= __('tarifs.features.advanced') ?>
                        </li>
                    </ul>
                    
                    <a href="register.php" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-center py-3 px-4 rounded-lg font-medium transition">
                        <?= __('tarifs.basic.button') ?>
                    </a>
                </div>
                
                <div class="pricing-card bg-white rounded-xl shadow-lg p-8 relative border-2 border-primary">
                    <div class="popular-badge bg-secondary text-white text-sm font-bold py-2 px-6 rounded-full shadow-md">
                        <?= __('tarifs.popular') ?>
                    </div>
                    
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= __('tarifs.pro.name') ?></h3>
                        <div class="text-4xl font-bold text-primary mb-1">
                            <?= __('tarifs.pro.price') ?>
                        </div>
                        <p class="text-gray-500"><?= __('tarifs.pro.period') ?></p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.invoices') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.clients') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.support') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.storage') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.advanced') ?>
                        </li>
                    </ul>
                    
                    <a href="register.php" class="block w-full bg-primary hover:bg-green-800 text-white text-center py-3 px-4 rounded-lg font-medium transition">
                        <?= __('tarifs.pro.button') ?>
                    </a>
                </div>
                
                <div class="pricing-card bg-white rounded-xl shadow-md p-8 relative border-2 border-gray-100">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= __('tarifs.enterprise.name') ?></h3>
                        <div class="text-4xl font-bold text-primary mb-1">
                            <?= __('tarifs.enterprise.price') ?>
                        </div>
                        <p class="text-gray-500"><?= __('tarifs.enterprise.period') ?></p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.invoices') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.clients') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.support') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.storage') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.team') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.priority') ?>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <?= __('tarifs.features.reports') ?>
                        </li>
                    </ul>
                    
                    <a href="contact.php" class="block w-full bg-gray-800 hover:bg-black text-white text-center py-3 px-4 rounded-lg font-medium transition">
                        <?= __('tarifs.enterprise.button') ?>
                    </a>
                </div>
            </div>

            <div class="bg-primary bg-opacity-5 rounded-2xl p-8 md:p-12 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4"><?= __('tarifs.cta_title') ?></h2>
                <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                    <?= __('tarifs.cta_subtitle') ?>
                </p>
                <a href="contact.php" class="inline-block bg-primary hover:bg-green-800 text-white py-3 px-8 rounded-lg font-medium text-lg transition">
                    <?= __('tarifs.cta_button') ?>
                </a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px) rotateX(5deg)';
                card.style.boxShadow = '0 20px 30px -10px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>