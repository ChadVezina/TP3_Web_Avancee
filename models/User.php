<?php
namespace App\Models;
use App\Models\CRUD;

class User extends CRUD {
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = ['username', 'email', 'password', 'created_at', 'privilege_id'];

    public function hashPassword($password, $cost = 10){
        $options = [
                'cost' => $cost
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options); 
    }


    /**
     * Attempt to authenticate a user and log in if successful.
     */
    public function checkUser($username, $password){
        $user = $this->unique('username',$username);
        if($user){
            if(password_verify($password, $user['password'])){
                session_regenerate_id();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['privilege_id'] = $user['privilege_id'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
                return true;
            }else{
                return false;   
            }
        }else{
            return false; 
        }
    }
}