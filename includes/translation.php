<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PhpFileLoader;

$translator = new Translator($_SESSION['lang'] ?? 'fr');
$translator->addLoader('php', new PhpFileLoader());

$translator->addResource('php', __DIR__ . '/../lang/fr.php', 'fr');
$translator->addResource('php', __DIR__ . '/../lang/ar.php', 'ar');

function __($key, $params = []) {
    global $translator;
    return $translator->trans($key, $params);
}