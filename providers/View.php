<?php

namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Providers\Auth;
use App\Providers\Language;

class View
{
    public static function render($template, $data = [])
    {
        // Initialiser le système de langue
        Language::init();

        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);
        $twig->addGlobal('logged_in', Auth::check());
        $twig->addGlobal('auth_user_id', Auth::id());
        $twig->addGlobal('auth_privilege_id', Auth::privilege_id());
        $twig->addGlobal('current_user', Auth::username());
        $twig->addGlobal('session', $_SESSION);

        // Ajouter les variables de langue
        $twig->addGlobal('current_language', Language::getCurrentLanguage());
        $twig->addGlobal('supported_languages', Language::getSupportedLanguages());

        // Ajouter la fonction de traduction à Twig
        $twig->addFunction(new \Twig\TwigFunction('t', function ($key, $params = []) {
            return Language::translate($key, $params);
        }));

        // Ajouter des fonctions helper pour les URLs avec langue
        $twig->addFunction(new \Twig\TwigFunction('lang_url', function ($path, $lang = null) {
            return Language::url($path, $lang);
        }));

        $twig->addFunction(new \Twig\TwigFunction('current_url_with_lang', function ($lang = null) {
            return Language::getCurrentUrlWithLang($lang);
        }));

        echo $twig->render("{$template}.php", $data);
    }

    public static function redirect($url)
    {
        // Préserver la langue dans les redirections
        $currentLang = Language::getCurrentLanguage();
        $separator = strpos($url, '?') !== false ? '&' : '?';

        if (strpos($url, 'lang=') === false) {
            $url .= $separator . 'lang=' . $currentLang;
        }

        header('location:' . BASE . '/' . ltrim($url, '/'));
    }
}
