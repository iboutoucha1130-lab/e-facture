<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/helpers.php';
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('cgu.title') ?> - efacture-maroc.com</title>
    
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
    
    <main class="flex-grow py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border-t-4 border-primary">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-primary mb-2"><?= __('cgu.page_title') ?></h1>
                    <p class="text-gray-600"><?= __('cgu.last_update') ?>: <?= date('d/m/Y') ?></p>
                </div>
                
                <div class="prose max-w-none">
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section1.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section1.content') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section2.title') ?></h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong><?= __('cgu.section2.item1.label') ?></strong>: <?= __('cgu.section2.item1.text') ?></li>
                            <li><strong><?= __('cgu.section2.item2.label') ?></strong>: <?= __('cgu.section2.item2.text') ?></li>
                            <li><strong><?= __('cgu.section2.item3.label') ?></strong>: <?= __('cgu.section2.item3.text') ?></li>
                            <li><strong><?= __('cgu.section2.item4.label') ?></strong>: <?= __('cgu.section2.item4.text') ?></li>
                        </ul>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section3.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section3.paragraph1') ?></p>
                        <p><?= __('cgu.section3.paragraph2') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section4.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section4.intro') ?></p>
                        <ul class="list-disc pl-5 space-y-2 mb-4">
                            <li><?= __('cgu.section4.item1') ?></li>
                            <li><?= __('cgu.section4.item2') ?></li>
                            <li><?= __('cgu.section4.item3') ?></li>
                            <li><?= __('cgu.section4.item4') ?></li>
                        </ul>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section5.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section5.paragraph1') ?></p>
                        <p><?= __('cgu.section5.paragraph2') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section6.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section6.intro') ?></p>
                        <ul class="list-disc pl-5 space-y-2 mb-4">
                            <li><?= __('cgu.section6.item1') ?></li>
                            <li><?= __('cgu.section6.item2') ?></li>
                        </ul>
                        <p><?= __('cgu.section6.conclusion') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section7.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section7.paragraph1') ?></p>
                        <p><?= __('cgu.section7.paragraph2') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section8.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section8.paragraph1') ?></p>
                        <p><?= __('cgu.section8.paragraph2') ?></p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2"><?= __('cgu.section9.title') ?></h2>
                        <p class="mb-4"><?= __('cgu.section9.content') ?></p>
                    </section>
                </div>
                
                <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="font-semibold text-gray-700 mb-2"><?= __('cgu.contact.title') ?></p>
                    <ul class="space-y-1">
                        <li class="flex items-center"><i class="fas fa-envelope mr-2 text-primary"></i> <?= __('cgu.contact.email') ?></li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2 text-primary"></i> <?= __('cgu.contact.phone') ?></li>
                        <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2 text-primary"></i> <?= __('cgu.contact.address') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>