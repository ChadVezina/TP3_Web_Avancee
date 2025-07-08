<?php

namespace App\Models;

use App\Models\CRUD;

class User extends CRUD
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = ['username', 'email', 'password', 'created_at', 'privilege_id'];

    /**
     * Hash password with security options
     */
    public function hashPassword($password, $cost = 12)
    {
        $options = [
            'cost' => $cost
        ];
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    /**
     * Create user with hashed password
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = $this->hashPassword($data['password']);
        }
        return $this->insert($data);
    }

    /**
     * Update user with optional password hashing
     */
    public function updateUser($data, $id)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = $this->hashPassword($data['password']);
        } else {
            // Remove password from update if empty (don't change existing password)
            unset($data['password']);
        }
        return $this->update($data, $id);
    }

    /**
     * Attempt to authenticate a user and log in if successful.
     */
    public function checkUser($username, $password)
    {
        $user = $this->unique('username', $username);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['privilege_id'] = $user['privilege_id'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
                $_SESSION['last_activity'] = time();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get user with privilege information
     */
    public function getUserWithPrivilege($id)
    {
        $sql = "SELECT u.*, p.privilege as privilege_name 
                FROM {$this->table} u 
                LEFT JOIN privileges p ON u.privilege_id = p.id 
                WHERE u.id = :id";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }
}
