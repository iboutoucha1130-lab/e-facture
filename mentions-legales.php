<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/helpers.php';

$page_title = __("mentions-legales.page_title");
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - efacture-maroc.com</title>
    
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
        .legal-section {
            border-left: 4px solid #006233;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }
        
        .legal-item {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .legal-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="flex-grow py-12">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white rounded-xl shadow-sm p-8 mb-8 border-t-4 border-primary">
                <h1 class="text-3xl font-bold text-primary mb-6"><?= __("mentions-legales.title") ?></h1>
                <p class="text-gray-600 mb-8"><?= __("mentions-legales.intro") ?></p>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section1.title") ?></h2>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.denomination") ?></h3>
                        <p class="text-gray-600"><?= __("mentions-legales.section1.denomination_value") ?></p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.forme_juridique") ?></h3>
                        <p class="text-gray-600"><?= __("mentions-legales.section1.forme_juridique_value") ?></p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.siege_social") ?></h3>
                        <p class="text-gray-600"><?= __("mentions-legales.section1.siege_social_value") ?></p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.telephone") ?></h3>
                        <p class="text-gray-600"><?= __("mentions-legales.section1.telephone_value") ?></p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.email") ?></h3>
                        <p class="text-gray-600">contact@efacture-maroc.com</p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.ice") ?></h3>
                        <p class="text-gray-600">001234567890123</p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.registre_commerce") ?></h3>
                        <p class="text-gray-600">RC Rabat 12345</p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.patente") ?></h3>
                        <p class="text-gray-600">1234567</p>
                    </div>
                    
                    <div class="legal-item">
                        <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __("mentions-legales.section1.cnss") ?></h3>
                        <p class="text-gray-600">J123456789</p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section2.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section2.content") ?></p>
                        <p class="text-gray-600 mt-2">
                            <?= __("mentions-legales.section2.hebergeur") ?><br>
                            <?= __("mentions-legales.section2.adresse") ?><br>
                            <?= __("mentions-legales.section2.telephone") ?>
                        </p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section3.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section3.content") ?></p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section4.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section4.content") ?></p>
                        <p class="text-gray-600 mt-2"><?= __("mentions-legales.section4.contact") ?> dpo@efacture-maroc.com</p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section5.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section5.content") ?></p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section6.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section6.content") ?></p>
                    </div>
                </div>
                
                <div class="legal-section">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?= __("mentions-legales.section7.title") ?></h2>
                    <div class="legal-item">
                        <p class="text-gray-600"><?= __("mentions-legales.section7.content") ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>