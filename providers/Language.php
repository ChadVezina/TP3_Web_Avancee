<?php

namespace App\Providers;

class Language
{
    private static $currentLanguage = 'fr';
    private static $translations = [];
    private static $supportedLanguages = ['fr', 'en', 'es'];

    /**
     * Initialiser le système de langue
     */
    public static function init()
    {
        // S'assurer que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détecter la langue depuis la session, URL ou navigateur
        self::detectLanguage();

        // Charger les traductions pour la langue courante
        self::loadTranslations();

        // Préserver la langue dans les redirections
        self::preserveLanguageInRedirects();
    }

    /**
     * Détecter la langue préférée
     */
    private static function detectLanguage()
    {
        // 1. Vérifier l'URL (paramètre GET) - priorité la plus haute
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supportedLanguages)) {
            self::setLanguage($_GET['lang']);
            return;
        }

        // 2. Vérifier la session
        if (isset($_SESSION['language']) && in_array($_SESSION['language'], self::$supportedLanguages)) {
            self::$currentLanguage = $_SESSION['language'];
            self::loadTranslations(); // Recharger les traductions
            return;
        }

        // 3. Détecter depuis le navigateur
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, self::$supportedLanguages)) {
                self::setLanguage($browserLang);
                return;
            }
        }

        // 4. Langue par défaut
        self::setLanguage('fr');
    }

    /**
     * Définir la langue courante
     */
    public static function setLanguage($language)
    {
        if (in_array($language, self::$supportedLanguages)) {
            self::$currentLanguage = $language;
            $_SESSION['language'] = $language;
            self::loadTranslations();
        }
    }

    /**
     * Obtenir la langue courante
     */
    public static function getCurrentLanguage()
    {
        return self::$currentLanguage;
    }

    /**
     * Obtenir les langues supportées
     */
    public static function getSupportedLanguages()
    {
        return self::$supportedLanguages;
    }

    /**
     * Charger les traductions
     */
    private static function loadTranslations()
    {
        $file = __DIR__ . "/../lang/" . self::$currentLanguage . ".php";

        if (file_exists($file)) {
            self::$translations = include $file;
        } else {
            self::$translations = [];
        }
    }

    /**
     * Traduire une clé
     */
    public static function translate($key, $params = [])
    {
        $translation = self::$translations[$key] ?? $key;

        // Remplacer les paramètres si fournis
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }

        return $translation;
    }

    /**
     * Fonction raccourcie pour la traduction
     */
    public static function t($key, $params = [])
    {
        return self::translate($key, $params);
    }

    /**
     * Obtenir l'URL actuelle avec le paramètre de langue
     */
    public static function getCurrentUrlWithLang($lang = null)
    {
        $lang = $lang ?? self::$currentLanguage;
        $url = $_SERVER['REQUEST_URI'];

        // Nettoyer les paramètres de langue existants
        $url = preg_replace('/[?&]lang=[a-z]{2}/', '', $url);

        // Ajouter le séparateur approprié
        $separator = strpos($url, '?') !== false ? '&' : '?';

        return $url . $separator . 'lang=' . $lang;
    }

    /**
     * Générer une URL avec la langue courante
     */
    public static function url($path, $lang = null)
    {
        $lang = $lang ?? self::$currentLanguage;
        $separator = strpos($path, '?') !== false ? '&' : '?';
        return BASE . '/' . ltrim($path, '/') . $separator . 'lang=' . $lang;
    }

    /**
     * Middleware pour préserver la langue dans toutes les redirections
     */
    public static function preserveLanguageInRedirects()
    {
        // Ajouter automatiquement le paramètre de langue aux redirections
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supportedLanguages)) {
            $_SESSION['preserve_lang_param'] = $_GET['lang'];
        }
    }
}
