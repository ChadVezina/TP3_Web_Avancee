<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\User;

class AuthController
{

    public function index()
    {
        return View::render('auth/index');
    }

    public function store($data)
    {
        $validator = new Validator();
        $validator->field('username', $data['username'])->required();
        $validator->field('password', $data['password'])->required();

        if ($validator->isSuccess()) {
            $user = new User;
            if ($user->checkUser($data['username'], $data['password'])) {
                return View::redirect('posts');
            } else {
                $errors = ['Invalid username or password'];
                return View::render('auth/index', ['errors' => $errors, 'user' => $data]);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('auth/index', ['errors' => $errors, 'user' => $data]);
        }
    }

    public function delete()
    {
        Auth::logout();
        return View::redirect('login');
    }
}
