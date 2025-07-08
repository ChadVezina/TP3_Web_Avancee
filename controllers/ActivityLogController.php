<?php

namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Models\ActivityLog;

class ActivityLogController
{
    /**
     * Display activity logs (Admin only)
     */
    public function index()
    {
        // Vérifier que l'utilisateur est connecté et est admin (privilege_id = 1)
        Auth::requireAuth();
        Auth::privilege(1);

        // Log this access
        $activityLog = new ActivityLog();
        $activityLog->logActivity('Accès au journal de bord', '/activity-logs');

        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get logs and total count
        $logs = $activityLog->getAllLogs($limit, $offset);
        $totalLogs = $activityLog->getLogsCount();
        $totalPages = ceil($totalLogs / $limit);

        // Get flash message if exists
        $flashMessage = null;
        if (isset($_SESSION['flash_message'])) {
            $flashMessage = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
        }

        return View::render('activity-log/index', [
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalLogs' => $totalLogs,
            'flash_message' => $flashMessage
        ]);
    }

    /**
     * Clear old logs (Admin only)
     */
    public function clear($data = [])
    {
        Auth::requireAuth();
        Auth::privilege(1);

        $activityLog = new ActivityLog();
        
        // Get deletion type from GET parameters or POST data
        $deleteType = isset($_GET['type']) ? $_GET['type'] : (isset($data['type']) ? $data['type'] : 'old');
        $deletedCount = 0;
        
        switch ($deleteType) {
            case '10':
                $sql = "DELETE FROM activity_logs ORDER BY created_at ASC LIMIT 10";
                break;
            case '50':
                $sql = "DELETE FROM activity_logs ORDER BY created_at ASC LIMIT 50";
                break;
            case '100':
                $sql = "DELETE FROM activity_logs ORDER BY created_at ASC LIMIT 100";
                break;
            case '500':
                $sql = "DELETE FROM activity_logs ORDER BY created_at ASC LIMIT 500";
                break;
            case 'all':
                $sql = "DELETE FROM activity_logs";
                break;
            default: // 'old' - 6 months
                $sql = "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
                break;
        }
        
        $stmt = $activityLog->prepare($sql);
        $deleted = $stmt->execute();
        $deletedCount = $stmt->rowCount();
        
        // Log this action AFTER clearing (only if we didn't delete all logs)
        if ($deleteType !== 'all' && $deletedCount > 0) {
            $activityLog->logActivity("Suppression de {$deletedCount} logs ({$deleteType})", '/activity-logs/clear');
        }
        
        if ($deleted) {
            $message = "Suppression réussie de {$deletedCount} logs.";
        } else {
            $message = "Aucun log à supprimer ou erreur lors de la suppression.";
        }
        
        // Redirect back to logs with message
        $_SESSION['flash_message'] = $message;
        View::redirect('activity-logs');
    }
}
