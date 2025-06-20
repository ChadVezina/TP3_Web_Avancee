<?php

namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Providers\Auth;

class View
{
    static public function render($template, $data = [])
    {
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);
        $twig->addGlobal('logged_in', Auth::check());
        $twig->addGlobal('current_user', Auth::username());
        $twig->addGlobal('auth_user', Auth::user());

        echo $twig->render($template . ".php", $data);
    }

    static public function redirect($url)
    {
        header('location:' . BASE . '/' . $url);
    }
}
