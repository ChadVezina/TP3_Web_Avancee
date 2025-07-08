<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

// Initialize security middleware
use App\Providers\SecurityMiddleware;

SecurityMiddleware::init();

require_once 'routes/web.php';
