<?php

$routePaths = [
    '/' => ['controller' => 'PageController', 'method' => 'login'],
    '/404' => ['controller' => 'PageController', 'method' => 'error404'],
    '/500' => ['controller' => 'PageController', 'method' => 'error500'],
    '/403' => ['controller' => 'PageController', 'method' => 'error403'],

    '/forgot' => ['controller' => 'PageController', 'method' => 'forgot'],
    '/register' => ['controller' => 'PageController', 'method' => 'register'],
    '/term' => ['controller' => 'PageController', 'method' => 'term'],

    '/dashboard' => ['controller' => 'PageController', 'method' => 'dashboard'],

    // Auth
    '/auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    '/auth/forgot' => ['controller' => 'AuthController', 'method' => 'forgot'],
    '/auth/register' => ['controller' => 'AuthController', 'method' => 'register'],
    '/auth/forgotValidate' => ['controller' => 'AuthController', 'method' => 'forgotValidate'],
    '/auth/profile' => ['controller' => 'AuthController', 'method' => 'profile'],

    // User
    '/user' => ['controller' => 'UserController', 'method' => 'index'],
    '/user/table' => ['controller' => 'UserController', 'method' => 'table'],
    '/user/id' => ['controller' => 'UserController', 'method' => 'id'],
    '/user/create' => ['controller' => 'UserController', 'method' => 'create'],
    '/user/update' => ['controller' => 'UserController', 'method' => 'update'],
    '/user/updatePassword' => ['controller' => 'UserController', 'method' => 'updatePassword'],
    '/user/delete' => ['controller' => 'UserController', 'method' => 'delete'],

    // User Role
    '/userRole' => ['controller' => 'UserRoleController', 'method' => 'index'],
    '/userRole/id' => ['controller' => 'UserRoleController', 'method' => 'id'],
    '/userRole/list' => ['controller' => 'UserRoleController', 'method' => 'list'],
    '/userRole/create' => ['controller' => 'UserRoleController', 'method' => 'create'],
    '/userRole/update' => ['controller' => 'UserRoleController', 'method' => 'update'],
    '/userRole/delete' => ['controller' => 'UserRoleController', 'method' => 'delete'],

    '/appAuthorization/save' => ['controller' => 'AppAuthorizationController', 'method' => 'save'],
    '/appAuthorization/byUserRoleId' => ['controller' => 'AppAuthorizationController', 'method' => 'byUserRoleId'],

    // Business
    '/business/update' => ['controller' => 'BusinessController', 'method' => 'update'],

    // Category
    '/category' => ['controller' => 'CategoryController', 'method' => 'index'],
    '/category/table' => ['controller' => 'CategoryController', 'method' => 'table'],
    '/category/search' => ['controller' => 'CategoryController', 'method' => 'search'],
    '/category/id' => ['controller' => 'CategoryController', 'method' => 'id'],
    '/category/create' => ['controller' => 'CategoryController', 'method' => 'create'],
    '/category/update' => ['controller' => 'CategoryController', 'method' => 'update'],
    '/category/delete' => ['controller' => 'CategoryController', 'method' => 'delete'],

    // Product
    '/product' => ['controller' => 'ProductController', 'method' => 'index'],
    '/product/table' => ['controller' => 'ProductController', 'method' => 'table'],
    '/product/search' => ['controller' => 'ProductController', 'method' => 'search'],
    '/product/id' => ['controller' => 'ProductController', 'method' => 'id'],
    '/product/create' => ['controller' => 'ProductController', 'method' => 'create'],
    '/product/update' => ['controller' => 'ProductController', 'method' => 'update'],
    '/product/delete' => ['controller' => 'ProductController', 'method' => 'delete'],

    // Customer
    '/customer' => ['controller' => 'CustomerController', 'method' => 'index'],
    '/customer/table' => ['controller' => 'CustomerController', 'method' => 'table'],
    '/customer/search' => ['controller' => 'CustomerController', 'method' => 'search'],
    '/customer/id' => ['controller' => 'CustomerController', 'method' => 'id'],
    '/customer/create' => ['controller' => 'CustomerController', 'method' => 'create'],
    '/customer/update' => ['controller' => 'CustomerController', 'method' => 'update'],
    '/customer/delete' => ['controller' => 'CustomerController', 'method' => 'delete'],

    // Business
    '/businessLocal' => ['controller' => 'BusinessLocalController', 'method' => 'index'],
    '/businessLocal/table' => ['controller' => 'BusinessLocalController', 'method' => 'table'],
    '/businessLocal/search' => ['controller' => 'BusinessLocalController', 'method' => 'search'],
    '/businessLocal/id' => ['controller' => 'BusinessLocalController', 'method' => 'id'],
    '/businessLocal/create' => ['controller' => 'BusinessLocalController', 'method' => 'create'],
    '/businessLocal/update' => ['controller' => 'BusinessLocalController', 'method' => 'update'],
    '/businessLocal/delete' => ['controller' => 'BusinessLocalController', 'method' => 'delete'],
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
