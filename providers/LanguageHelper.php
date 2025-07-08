<?php

namespace App\Providers;

/**
 * Helper pour la gestion des langues dans les contrôleurs
 */
class LanguageHelper
{
    /**
     * Rediriger en préservant la langue courante
     */
    public static function redirect($url)
    {
        $currentLang = Language::getCurrentLanguage();
        $separator = strpos($url, '?') !== false ? '&' : '?';

        if (strpos($url, 'lang=') === false) {
            $url .= $separator . 'lang=' . $currentLang;
        }

        header('Location: ' . BASE . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Obtenir une URL avec la langue courante
     */
    public static function url($path)
    {
        return Language::url($path);
    }

    /**
     * Définir un message flash avec traduction
     */
    public static function setFlashMessage($type, $key, $params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][$type] = Language::translate($key, $params);
    }

    /**
     * Obtenir et nettoyer les messages flash
     */
    public static function getFlashMessages()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }

    /**
     * Valider les données de formulaire avec traductions
     */
    public static function validateForm($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';

            foreach ($fieldRules as $rule => $ruleValue) {
                switch ($rule) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = Language::translate('validation.required');
                        }
                        break;

                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = Language::translate('validation.email');
                        }
                        break;

                    case 'min_length':
                        if (!empty($value) && strlen($value) < $ruleValue) {
                            $errors[$field][] = Language::translate('validation.min_length', ['min' => $ruleValue]);
                        }
                        break;

                    case 'max_length':
                        if (!empty($value) && strlen($value) > $ruleValue) {
                            $errors[$field][] = Language::translate('validation.max_length', ['max' => $ruleValue]);
                        }
                        break;

                    case 'confirmed':
                        $confirmField = $data[$field . '_confirmation'] ?? '';
                        if ($value !== $confirmField) {
                            $errors[$field][] = Language::translate('validation.confirmed');
                        }
                        break;
                }
            }
        }

        return $errors;
    }
}
