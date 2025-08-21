<?php
session_start();
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/helpers.php';

$lang = $_SESSION['lang'] ?? 'fr';
$translations = require __DIR__ . "/lang/$lang.php";
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('contact.title') ?> - efacture-maroc.com</title>
    
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
                <div class="md:flex">
                    <div class="md:w-1/2 bg-primary p-8 text-white">
                        <h2 class="text-2xl font-bold mb-6"><?= __('contact.contact_us') ?></h2>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3"><i class="fas fa-map-marker-alt mr-2"></i> <?= __('contact.address') ?></h3>
                            <p><?= __('contact.address_value') ?></p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3"><i class="fas fa-phone-alt mr-2"></i> <?= __('contact.phone') ?></h3>
                            <p><?= __('contact.phone_value') ?></p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3"><i class="fas fa-envelope mr-2"></i> <?= __('contact.email') ?></h3>
                            <p><?= __('contact.email_value') ?></p>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-3"><?= __('contact.follow_us') ?></h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-white hover:text-gray-200 text-2xl"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="text-white hover:text-gray-200 text-2xl"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-white hover:text-gray-200 text-2xl"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="text-white hover:text-gray-200 text-2xl"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:w-1/2 p-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6"><?= __('contact.send_message') ?></h2>
                        
                        <form action="process_contact.php" method="POST" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1"><?= __('contact.form.fullname') ?></label>
                                <input type="text" id="name" name="name" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="<?= __('contact.form.fullname_placeholder') ?>">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?= __('contact.form.email') ?></label>
                                <input type="email" id="email" name="email" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="<?= __('contact.form.email_placeholder') ?>">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1"><?= __('contact.form.phone') ?></label>
                                <input type="tel" id="phone" name="phone" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="<?= __('contact.form.phone_placeholder') ?>">
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1"><?= __('contact.form.subject') ?></label>
                                <select id="subject" name="subject" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="question"><?= __('contact.form.subject_options.general') ?></option>
                                    <option value="support"><?= __('contact.form.subject_options.support') ?></option>
                                    <option value="partnership"><?= __('contact.form.subject_options.partnership') ?></option>
                                    <option value="other"><?= __('contact.form.subject_options.other') ?></option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1"><?= __('contact.form.message') ?></label>
                                <textarea id="message" name="message" rows="4" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="<?= __('contact.form.message_placeholder') ?>"></textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full bg-primary hover:bg-green-800 text-white font-medium py-2 px-4 rounded-md transition duration-300">
                                    <?= __('contact.form.submit') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>