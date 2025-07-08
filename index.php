<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

// Initialize security middleware
use App\Providers\SecurityMiddleware;
use App\Providers\Language;

SecurityMiddleware::init();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize language system
Language::init();

require_once 'routes/web.php';
