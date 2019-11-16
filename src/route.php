<?php

$routePaths = [
    '/' => ['controller' => 'PageController', 'method' => 'login'],
    '/404' => ['controller' => 'PageController', 'method' => 'error404'],
    '/500' => ['controller' => 'PageController', 'method' => 'error500'],
    '/403' => ['controller' => 'PageController', 'method' => 'error403'],

    '/dashboard' => ['controller' => 'PageController', 'method' => 'dashboard'],
    '/doc' => ['controller' => 'PageController', 'method' => 'doc'],

    // Auth
    '/auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/auth/forgot' => ['controller' => 'AuthController', 'method' => 'forgot'],
    '/auth/forgotValidate' => ['controller' => 'AuthController', 'method' => 'forgotValidate'],
    '/auth/profile' => ['controller' => 'AuthController', 'method' => 'profile'],

    // User
    '/user' => ['controller' => 'UserController', 'method' => 'index'],
    '/user/id' => ['controller' => 'UserController', 'method' => 'id'],
    '/user/create' => ['controller' => 'UserController', 'method' => 'create'],
    '/user/update' => ['controller' => 'UserController', 'method' => 'update'],
    '/user/updatePassword' => ['controller' => 'UserController', 'method' => 'updatePassword'],
    '/user/delete' => ['controller' => 'UserController', 'method' => 'delete'],

    // Customer
    '/customer' => ['controller' => 'CustomerController', 'method' => 'index'],
    '/customer/table' => ['controller' => 'CustomerController', 'method' => 'table'],
    '/customer/id' => ['controller' => 'CustomerController', 'method' => 'id'],
    '/customer/create' => ['controller' => 'CustomerController', 'method' => 'create'],
    '/customer/update' => ['controller' => 'CustomerController', 'method' => 'update'],
    '/customer/delete' => ['controller' => 'CustomerController', 'method' => 'delete'],
];

$apiPublicPath = [
    '/setting' => ['controller' => 'PageController', 'method' => 'adminAvailabilities'],
    '/months' => ['controller' => 'AvailabilityController', 'method' => 'index'],
    '/parameters' => ['controller' => 'AvailabilityController', 'method' => 'parameters'],
    '/availabilities' => ['controller' => 'AvailabilityController', 'method' => 'availabilities'],
    '/sendEmail' => ['controller' => 'AvailabilityController', 'method' => 'sendEmail'],
];

class Router
{
    public $url;
    public $controller;
    public $method;
    public $param;

    public function __construct()
    {
        $this->url = URL;
        $this->matchRoute();
    }

    private function matchRoute()
    {
        global $routePaths;
        global $apiPublicPath;
        $path = null;

        if (isset($routePaths[$this->url])) {
            $path = $routePaths[$this->url];
        } else if (preg_match('/^\/api\/v1\/public/', $this->url)) {
            $url = '/' . trim(preg_replace('/^\/api\/v1\/public/', '', $this->url), '/');
            $path = isset($apiPublicPath[$url]) ? $apiPublicPath[$url] : $routePaths['/404'];
        } else {
            $path = $routePaths['/404'];
        }

        $this->controller = $path['controller'];
        $this->method = $path['method'];

        require_once CONTROLLER_PATH . "/{$this->controller}.php";
    }

    public function run()
    {
        try {
            $database = new Database();

            $controller = new $this->controller($database->getConnection());
            $method = $this->method;
            $controller->$method();
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->log($e);
        } catch (Exception $e) {
            $this->log($e);
            header('Location: ' . URL_PATH . '/500?message=' . urlencode($e->getMessage()));
        }
    }

    private function log(Exception $e)
    {
        $ipClient = '';
        $ipProxy = '';
        $ipServer = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ipClient = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ipProxy = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipServer = $_SERVER['REMOTE_ADDR'];
        }
        $error = 'PHP Fatal error | URL : ' . $_SERVER['REQUEST_URI'] . "\n" . 'IP : ' . $ipClient . ' | ' . $ipProxy . ' | ' . $ipServer . "\n" . ' ERROR index : ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
        error_log($error);
    }
}
