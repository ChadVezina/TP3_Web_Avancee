<?php

namespace App\Models;

use App\Models\CRUD;

class ActivityLog extends CRUD
{
    protected $table = "activity_logs";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'email', 'privilege_level', 'ip_address', 'user_agent', 'action', 'page_visited', 'created_at'];

    /**
     * Log user activity
     */
    public function logActivity($action, $pageVisited = null)
    {
        // Get client IP address (handling proxy and load balancers)
        $ipAddress = $this->getClientIpAddress();

        // Get user information
        $userId = null;
        $email = 'visiteur';
        $privilegeLevel = 'visiteur';

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $email = $_SESSION['email'] ?? 'unknown';
            $privilegeLevel = $_SESSION['privilege_id'] ?? 'unknown';
        }

        // Get current page if not provided
        if ($pageVisited === null) {
            $pageVisited = $_SERVER['REQUEST_URI'] ?? '';
        }

        $data = [
            'user_id' => $userId,
            'email' => $email,
            'privilege_level' => $privilegeLevel,
            'ip_address' => $ipAddress,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'action' => $action,
            'page_visited' => $pageVisited,
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    /**
     * Get client IP address considering proxies
     */
    private function getClientIpAddress()
    {
        $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        $finalIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        
        if ($finalIp === '::1') {
            $finalIp = '127.0.0.1';
        }
        
        return $finalIp;
    }

    /**
     * Get all activity logs with pagination
     */
    public function getAllLogs($limit = 50, $offset = 0)
    {
        $sql = "SELECT al.*, u.username, 
                al.page_visited as url,
                CASE 
                    WHEN al.ip_address = '::1' THEN '127.0.0.1'
                    ELSE al.ip_address 
                END as ip_address
                FROM {$this->table} al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get logs count for pagination
     */
    public function getLogsCount()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
