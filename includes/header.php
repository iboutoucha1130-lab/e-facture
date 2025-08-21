<?php
require_once __DIR__ . '/helpers.php';

$lang = $_SESSION['lang'] ?? 'fr';
$translations = require __DIR__ . "/../lang/$lang.php";
?>
<header class="bg-primary text-white py-4 px-4 md:px-8 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <div class="logo flex items-center">
            <a href="index.php" class="flex items-center">
                <img src="assets/images/logo.png" alt="efacture-maroc" class="h-10 mr-3">
                <span class="text-xl font-bold">efacture-maroc.com</span>
            </a>
        </div>
        
        <nav>
            <ul class="flex space-x-6 items-center">
                <li><a href="index.php" class="hover:text-gray-200 font-medium"><?= __('header.home') ?></a></li>
                <li><a href="tarifs.php" class="hover:text-gray-200 font-medium"><?= __('header.pricing') ?></a></li>
                <li><a href="contact.php" class="hover:text-gray-200 font-medium"><?= __('header.contact') ?></a></li>
                <li><a href="cgu.php" class="hover:text-gray-200 font-medium"><?= __('header.terms') ?></a></li>
                
                <?php if(isset($_SESSION['user'])): ?>
                    <li><a href="dashboard.php" class="hover:text-gray-200 font-medium"><?= __('header.dashboard') ?></a></li>
                    <li><a href="parametres.php" class="hover:text-gray-200 font-medium"><?= __('header.settings') ?></a></li>
                    <li><a href="logout.php" class="bg-secondary hover:bg-red-700 px-4 py-2 rounded-md font-medium"><?= __('header.logout') ?></a></li>
                <?php else: ?>
                    <li><a href="login.php" class="bg-secondary hover:bg-red-700 px-4 py-2 rounded-md font-medium"><?= __('header.login') ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>