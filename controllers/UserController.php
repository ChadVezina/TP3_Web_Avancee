<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\User;

class UserController
{

    public function create()
    {
        Auth::requireGuest();
        return View::render('user/create');
    }

    public function store($data)
    {
        Auth::requireGuest();

        $validator = new Validator;
        $validator->field('username', $data['username'])->min(2)->max(50)->required()->unique('User');
        $validator->field('email', $data['email'])->min(2)->max(100)->required()->email()->unique('User');
        $validator->field('password', $data['password'])->min(6)->max(20)->required();

        if ($validator->isSuccess()) {
            $user = new User;
            $data['password'] = $user->hashPassword($data['password']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $insert = $user->insert($data);

            if ($insert) {
                return View::redirect('login');
            } else {
                return View::render('error', ['message' => 'Could not create user! Please try again.']);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('user/create', ['errors' => $errors, 'user' => $data]);
        }
    }
}
