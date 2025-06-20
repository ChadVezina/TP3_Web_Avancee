<?php

namespace App\Providers;

use App\Models\User;

class Auth
{

    /**
     * Check if user is authenticated
     */
    public static function check()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Get the currently authenticated user
     */
    public static function user()
    {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'] ?? null
            ];
        }
        return null;
    }

    /**
     * Get the authenticated user's ID
     */
    public static function id()
    {
        return self::check() ? $_SESSION['user_id'] : null;
    }

    /**
     * Get the authenticated user's username
     */
    public static function username()
    {
        return self::check() ? $_SESSION['username'] : null;
    }

    /**
     * Attempt to authenticate a user
     */
    public static function attempt($username, $password)
    {
        $userModel = new User();
        $foundUser = $userModel->unique('username', $username);

        if ($foundUser && password_verify($password, $foundUser['password'])) {
            self::login($foundUser);
            return true;
        }

        return false;
    }

    /**
     * Log in a user
     */
    public static function login($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;
    }

    /**
     * Log out the current user
     */
    public static function logout()
    {

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Check if user is guest (not authenticated)
     */
    public static function guest()
    {
        return !self::check();
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth()
    {
        if (self::guest()) {
            View::redirect('login');
            exit;
        }
    }

    /**
     * Require guest - redirect to posts if already authenticated
     */
    public static function requireGuest()
    {
        if (self::check()) {
            View::redirect('posts');
            exit;
        }
    }
}
