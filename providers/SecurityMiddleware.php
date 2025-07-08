<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Providers\View;
use App\Providers\Auth;

class SecurityMiddleware
{
    private static $activityLog;

    /**
     * Initialize the middleware
     */
    public static function init()
    {
        self::$activityLog = new ActivityLog();

        // Start secure session
        self::startSecureSession();

        // Log page visit
        self::logPageVisit();

        // Verify session security
        self::verifySessionSecurity();
    }

    /**
     * Start a secure session with enhanced security settings
     */
    private static function startSecureSession()
    {
        // Configure session security settings
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.gc_maxlifetime', 3600); // 1 hour

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regenerate session ID periodically for security
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }

    /**
     * Log every page visit
     */
    private static function logPageVisit()
    {
        $action = 'Visite de page';
        $page = $_SERVER['REQUEST_URI'] ?? '/';

        // Don't log AJAX requests or asset requests
        if (self::shouldLogRequest()) {
            self::$activityLog->logActivity($action, $page);
        }
    }

    /**
     * Verify session security and detect session hijacking
     */
    private static function verifySessionSecurity()
    {
        if (Auth::check()) {
            // Check for session hijacking
            $currentFingerprint = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);

            if (!isset($_SESSION['fingerPrint']) || $_SESSION['fingerPrint'] !== $currentFingerprint) {
                // Possible session hijacking detected
                self::$activityLog->logActivity('Tentative de hijacking de session détectée');
                Auth::logout();
                View::redirect('login?error=security');
                exit;
            }

            // Check session timeout
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
                self::$activityLog->logActivity('Session expirée');
                Auth::logout();
                View::redirect('login?error=timeout');
                exit;
            }

            $_SESSION['last_activity'] = time();
        }
    }

    /**
     * Log user login attempts
     */
    public static function logLoginAttempt($username, $success, $reason = '')
    {
        $data = [
            'user_id' => $success ? Auth::id() : null,
            'action' => $success ? 'connexion réussie' : 'tentative de connexion échouée',
            'entity_type' => 'authentification',
            'entity_id' => null,
            'url' => self::getCurrentUrl(),
            'ip_address' => self::getRealIpAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'details' => $success ? "Utilisateur: $username" : "Utilisateur: $username - Raison: $reason",
            'created_at' => date('Y-m-d H:i:s')
        ];

        $activityLog = new ActivityLog();
        $activityLog->insert($data);
    }

    /**
     * Log user logout
     */
    public static function logLogout()
    {
        self::$activityLog->logActivity('Déconnexion', '/logout');
    }

    /**
     * Log CRUD operations
     */
    public static function logCrudOperation($action, $entity, $entityId)
    {
        if (!Auth::check()) return;

        $data = [
            'user_id' => Auth::id(),
            'action' => $action . ' de ' . $entity,
            'entity_type' => $entity,
            'entity_id' => $entityId,
            'url' => self::getCurrentUrl(), // Utilisez cette méthode
            'ip_address' => self::getRealIpAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $activityLog = new ActivityLog();
        $activityLog->insert($data);
    }

    /**
     * Check if request should be logged
     */
    private static function shouldLogRequest()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        // Don't log asset requests
        $skipPatterns = [
            '/\.css/',
            '/\.js/',
            '/\.png/',
            '/\.jpg/',
            '/\.jpeg/',
            '/\.gif/',
            '/\.ico/',
            '/\.woff/',
            '/\.ttf/',
            '/\.svg/',
            '/ajax/',
            '/api/'
        ];

        foreach ($skipPatterns as $pattern) {
            if (preg_match($pattern, $uri)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Apply rate limiting for login attempts
     */
    public static function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900) // 15 minutes
    {
        $key = 'rate_limit_' . md5($identifier);

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'first_attempt' => time()];
        }

        $rateLimitData = $_SESSION[$key];

        // Reset if time window passed
        if (time() - $rateLimitData['first_attempt'] > $timeWindow) {
            $_SESSION[$key] = ['attempts' => 0, 'first_attempt' => time()];
            return true;
        }

        // Check if limit exceeded
        if ($rateLimitData['attempts'] >= $maxAttempts) {
            self::$activityLog->logActivity('Limite de tentatives de connexion dépassée', '/login');
            return false;
        }

        return true;
    }

    /**
     * Increment rate limit counter
     */
    public static function incrementRateLimit($identifier)
    {
        $key = 'rate_limit_' . md5($identifier);

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'first_attempt' => time()];
        }

        $_SESSION[$key]['attempts']++;
    }

    // Pour capturer l'URL correctement
    private static function getCurrentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        return $protocol . $host . $uri;
    }

    private static function getRealIpAddress()
    {
        // Vérifier plusieurs sources pour obtenir la vraie IP
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED'
        ];

        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) && !empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);

                // Valider l'IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Si on est en développement local, retourner une IP plus lisible
        $localIp = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        if ($localIp === '::1') {
            return '127.0.0.1 (localhost)';
        }

        return $localIp;
    }
}
