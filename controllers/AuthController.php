<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;

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

        if ($validator->isSuccess()) {
            if (Auth::attempt($data['username'], $data['password'])) {
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
