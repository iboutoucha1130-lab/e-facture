<?php
if (!function_exists('__')) {
    function __($key, $replace = []) {
        global $translations;
        // Vérifier si la clé contient un point (pour les sous-tableaux)
        $keys = explode('.', $key);
        $translation = $translations;
        foreach ($keys as $k) {
            if (isset($translation[$k])) {
                $translation = $translation[$k];
            } else {
                $translation = $key; // retourne la clé si non trouvée
                break;
            }
        }
        
        // Si on a une chaîne, on effectue les remplacements
        if (is_string($translation)) {
            foreach ($replace as $placeholder => $value) {
                $translation = str_replace(":$placeholder", $value, $translation);
            }
        } else {
            // Si ce n'est pas une chaîne, on retourne la clé
            $translation = $key;
        }
        
        return $translation;
    }
}

// Initialisation des traductions si elles ne sont pas déjà définies
if (!isset($translations)) {
    $lang = $_SESSION['lang'] ?? 'fr';
    $translations = require __DIR__ . "/../lang/$lang.php";
}