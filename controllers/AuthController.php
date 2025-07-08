<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\SecurityMiddleware;
use App\Models\User;

class AuthController
{

    public function index()
    {
        Auth::requireGuest();
        return View::render('auth/index');
    }

    public function store($data)
    {
        Auth::requireGuest();

        $validator = new Validator();
        $validator->field('username', $data['username'])->required();
        $validator->field('password', $data['password'])->required();

        if (!$validator->isSuccess()) {
            $errors = $validator->getErrors();
            return View::render('auth/index', ['errors' => $errors, 'user' => $data]);
        }

        // Check rate limiting
        $identifier = $_SERVER['REMOTE_ADDR'] . '_' . $data['username'];
        if (!SecurityMiddleware::checkRateLimit($identifier)) {
            $errors = ['Trop de tentatives de connexion. Veuillez attendre 15 minutes.'];
            return View::render('auth/index', ['errors' => $errors, 'user' => $data]);
        }

        $user = new User;
        if ($user->checkUser($data['username'], $data['password'])) {
            // Successful login
            SecurityMiddleware::logLoginAttempt($data['username'], true);
            return View::redirect('posts');
        } else {
            // Failed login
            SecurityMiddleware::incrementRateLimit($identifier);
            SecurityMiddleware::logLoginAttempt($data['username'], false, 'Nom d\'utilisateur ou mot de passe incorrect');

            $errors = ['Nom d\'utilisateur ou mot de passe incorrect'];
            return View::render('auth/index', ['errors' => $errors, 'user' => $data]);
        }
    }

    public function delete()
    {
        if (Auth::check()) {
            SecurityMiddleware::logLogout();
        }
        Auth::logout();
        return View::redirect('login');
    }
}
