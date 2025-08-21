<?php
require_once __DIR__ . '/includes/config.php';
session_start();
$isLoggedIn = isset($_SESSION['user']);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('index.title') ?> - efacture-maroc.com</title>
    
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
        .hero-bg {
            background: radial-gradient(circle at top right, rgba(0, 98, 51, 0.1) 0%, rgba(255, 255, 255, 0) 30%);
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="flex-grow">
        <section class="hero-bg py-16 md:py-24">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl font-bold text-primary mb-6">
                        <?= __('index.hero.title') ?>
                    </h1>
                    <p class="text-xl text-gray-600 mb-10">
                        <?= __('index.hero.subtitle') ?>
                    </p>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <?php if(!$isLoggedIn): ?>
                            <a href="login.php" class="bg-secondary hover:bg-red-700 text-white px-8 py-3 rounded-md font-medium text-lg shadow-md transition">
                                <?= __('index.hero.login_button') ?>
                            </a>
                            <a href="register.php" class="bg-primary hover:bg-green-800 text-white px-8 py-3 rounded-md font-medium text-lg shadow-md transition">
                                <?= __('index.hero.register_button') ?>
                            </a>
                        <?php else: ?>
                            <a href="dashboard.php" class="bg-primary hover:bg-green-800 text-white px-8 py-3 rounded-md font-medium text-lg shadow-md transition">
                                <?= __('index.hero.dashboard_button') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-primary mb-12">
                    <?= __('index.features.title') ?>
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-4">
                            <i class="fas fa-balance-scale text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= __('index.features.item1.title') ?></h3>
                        <p class="text-gray-600"><?= __('index.features.item1.description') ?></p>
                    </div>
                    
                    <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                            <i class="fas fa-language text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= __('index.features.item2.title') ?></h3>
                        <p class="text-gray-600"><?= __('index.features.item2.description') ?></p>
                    </div>
                    
                    <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-4">
                            <i class="fas fa-credit-card text-primary text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= __('index.features.item3.title') ?></h3>
                        <p class="text-gray-600"><?= __('index.features.item3.description') ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-primary mb-12">
                    <?= __('index.testimonials.title') ?>
                </h2>
                
                <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8 border-l-4 border-secondary">
                    <blockquote class="text-gray-600 italic text-lg">
                        "<?= __('index.testimonials.quote') ?>"
                    </blockquote>
                    <cite class="block mt-4 font-semibold text-gray-800">- <?= __('index.testimonials.author') ?></cite>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>