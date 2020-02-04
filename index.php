<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require_once __DIR__ . '/src/autoload.php';

    session_start();

    $router = new Router();
    $router->run();
