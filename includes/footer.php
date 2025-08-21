<?php
if (!isset($translations)) {
    $lang = $_SESSION['lang'] ?? 'fr';
    $translations = require __DIR__ . '/../lang/' . $lang . '.php';
}
?>
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="footer-content">
            <p class="mb-4 text-center">&copy; <?= date('Y') ?> efacture-maroc.com - <?= __('footer.copyright') ?></p>
            <nav class="flex justify-center space-x-6 flex-wrap">
                <a href="mentions-legales.php" class="text-gray-400 hover:text-white transition"><?= __('footer.legal_mentions') ?></a>
                <a href="cgu.php" class="text-gray-400 hover:text-white transition"><?= __('footer.tos') ?></a>
                <a href="contact.php" class="text-gray-400 hover:text-white transition"><?= __('footer.contact') ?></a>
            </nav>
        </div>
    </div>
</footer>