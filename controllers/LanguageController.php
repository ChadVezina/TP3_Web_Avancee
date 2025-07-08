<?php

namespace App\Controllers;

use App\Providers\Language;
use App\Providers\View;

class LanguageController
{
    /**
     * Changer la langue et rediriger
     */
    public function switch($data)
    {
        $language = $data['lang'] ?? 'fr';
        Language::setLanguage($language);

        // Rediriger vers la page précédente ou l'accueil
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE;

        // Nettoyer l'URL de référence des paramètres de langue existants
        $referer = preg_replace('/[?&]lang=[a-z]{2}/', '', $referer);

        // Ajouter le séparateur approprié pour le nouveau paramètre
        $separator = strpos($referer, '?') !== false ? '&' : '?';

        header("Location: {$referer}{$separator}lang={$language}");
        exit;
    }
}
