<?php
if (!function_exists('__')) {
    function __($key, $replace = []) {
        global $translations;
        $keys = explode('.', $key);
        $translation = $translations;
        
        foreach ($keys as $k) {
            if (isset($translation[$k])) {
                $translation = $translation[$k];
            } else {
                return $key;
            }
        }
        
        if (is_string($translation)) {
            foreach ($replace as $placeholder => $value) {
                $translation = str_replace(":$placeholder", $value, $translation);
            }
        }
        
        return $translation;
    }
}

if (!isset($translations)) {
    $lang = $_SESSION['lang'] ?? 'fr';
    $translations = require __DIR__ . "/lang/$lang.php";
}