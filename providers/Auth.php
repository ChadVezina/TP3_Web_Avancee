<?php

namespace App\Providers;

use App\Providers\View;

class Auth
{

    /**
     * Check if user is authenticated
     */
    public static function check()
    {
        return isset($_SESSION['fingerPrint']) AND $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Check if user has the min privilege
     */
    public static function has_privilege($id){
        return $_SESSION['privilege_id'] <= $id;
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
     * Get the authenticated user's privilege ID
     */
    public static function privilege_id()
    {
        return self::check() ? $_SESSION['privilege_id'] : 3;
    }

    /**
     * Get the authenticated user's username
     */
    public static function username()
    {
        return self::check() ? $_SESSION['username'] : null;
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
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth()
    {
        if (!self::check()) {
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
    public static function privilege($id){
        if(!self::has_privilege($id)){
            View::redirect('login');
            exit();
        }
    }
}