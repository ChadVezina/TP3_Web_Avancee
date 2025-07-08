<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Models\Privilege;
use App\Models\User;

class UserController
{

    public function create()
    {
        $privilege = new Privilege;
        $privileges = $privilege->select(); 
        return View::render('user/create', ['privileges' => $privileges]);
    }

    public function store($data)
    {
        $validator = new Validator;
        $validator->field('username', $data['username'])->min(2)->max(50)->required()->unique('User');
        $validator->field('email', $data['email'])->min(2)->max(100)->required()->email()->unique('User');
        $validator->field('password', $data['password'])->min(6)->max(20)->required();
        $validator->field('privilege_id', $data['privilege_id'], 'Privilege')->required();

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
            $privilege = new Privilege;
            $privileges = $privilege->select();
            return View::render('user/create', ['errors' => $errors, 'user' => $data,'privileges' => $privileges]);
        }
    }
}