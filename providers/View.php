<?php

namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Providers\Auth;

class View
{
    public static function render($template, $data = [])
    {
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);
        $twig->addGlobal('logged_in', Auth::check());
        $twig->addGlobal('auth_user_id', Auth::id());
        $twig->addGlobal('auth_privilege_id', Auth::privilege_id());
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render("{$template}.php", $data);
    }

    public static function redirect($url)
    {
        header('location:' . BASE . '/' . $url);
    }
}