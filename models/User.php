<?php
namespace App\Models;
use App\Models\CRUD;

class User extends CRUD {
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = ['username', 'email', 'password', 'created_at'];

    public function hashPassword($password, $cost = 10){
        $options = [
                'cost' => $cost
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options); 
    }
}