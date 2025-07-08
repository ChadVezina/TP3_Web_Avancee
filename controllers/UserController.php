<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Providers\SecurityMiddleware;
use App\Models\Privilege;
use App\Models\User;

class UserController
{
    public function create()
    {
        return View::render('user/create');
    }

    public function store($data)
    {
        $validator = new Validator;
        $validator->field('username', $data['username'])->min(2)->max(50)->required()->unique('User');
        $validator->field('email', $data['email'])->min(2)->max(100)->required()->email()->unique('User');
        $validator->field('password', $data['password'])->min(8)->max(50)->required();

        if ($validator->isSuccess()) {
            $user = new User;
            $data['created_at'] = date('Y-m-d H:i:s');
            // Force privilege_id to 3 (user) for public registration
            $data['privilege_id'] = 3;

            // Use the secure createUser method that automatically hashes passwords
            $insert = $user->createUser($data);

            if ($insert) {
                SecurityMiddleware::logCrudOperation('création', 'utilisateur', $insert);
                return View::redirect('login');
            } else {
                return View::render('error', ['message' => 'Could not create user! Please try again.']);
            }
        } else {
            $errors = $validator->getErrors();
            return View::render('user/create', ['errors' => $errors, 'user' => $data]);
        }
    }

    /**
     * Admin user creation (allows setting any privilege level)
     */
    public function adminCreate()
    {
        // Vérifier que l'utilisateur est admin
        Auth::requireAuth();
        Auth::privilege(1);

        $privilege = new Privilege;
        $privileges = $privilege->select();
        return View::render('user/admin-create', ['privileges' => $privileges]);
    }

    /**
     * Store admin created user
     */
    public function adminStore($data)
    {
        // Vérifier que l'utilisateur est admin
        Auth::requireAuth();
        Auth::privilege(1);

        $validator = new Validator;
        $validator->field('username', $data['username'])->min(2)->max(50)->required()->unique('User');
        $validator->field('email', $data['email'])->min(2)->max(100)->required()->email()->unique('User');
        $validator->field('password', $data['password'])->min(8)->max(50)->required();
        $validator->field('privilege_id', $data['privilege_id'], 'Privilege')->required();

        if ($validator->isSuccess()) {
            $user = new User;
            $data['created_at'] = date('Y-m-d H:i:s');

            // Use the secure createUser method that automatically hashes passwords
            $insert = $user->createUser($data);

            if ($insert) {
                SecurityMiddleware::logCrudOperation('création d\'utilisateur (admin)', 'utilisateur', $insert);
                return View::redirect('activity-logs');
            } else {
                return View::render('error', ['message' => 'Could not create user! Please try again.']);
            }
        } else {
            $errors = $validator->getErrors();
            $privilege = new Privilege;
            $privileges = $privilege->select();
            return View::render('user/admin-create', ['errors' => $errors, 'user' => $data, 'privileges' => $privileges]);
        }
    }
}
