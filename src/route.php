<?php

$routePublicPaths = [
    '/' => ['controller' => 'PageController', 'method' => 'index'],
    '/404' => ['controller' => 'PageController', 'method' => 'error404'],
    '/500' => ['controller' => 'PageController', 'method' => 'error500'],
    '/403' => ['controller' => 'PageController', 'method' => 'error403'],
    '/term' => ['controller' => 'PageController', 'method' => 'term'],

    '/forgot' => ['controller' => 'PublicCompanyController', 'method' => 'forgot'],
    '/forgot/validate' => ['controller' => 'PublicCompanyController', 'method' => 'forgotValidate'],
    '/register' => ['controller' => 'PublicCompanyController', 'method' => 'register'],
    '/login' => ['controller' => 'PublicCompanyController', 'method' => 'login'],
    '/login/fa2' => ['controller' => 'PublicCompanyController', 'method' => 'postLogin'],

    '/manager/forgot' => ['controller' => 'PublicManagerController', 'method' => 'forgot'],
    '/manager/forgot/validate' => ['controller' => 'PublicManagerController', 'method' => 'forgotValidate'],
    '/manager/register' => ['controller' => 'PublicManagerController', 'method' => 'register'],
    '/manager/login' => ['controller' => 'PublicManagerController', 'method' => 'login'],
    '/manager/login/fa2' => ['controller' => 'PublicManagerController', 'method' => 'postLogin'],
    '/manager/term' => ['controller' => 'PublicManagerController', 'method' => 'term'],
];

class Router
{
    public $url;
    public $controller;
    public $method;

    public function __construct()
    {
        $this->url = URL;
        $this->matchRoute();
    }

    private function matchRoute()
    {
        global $routePublicPaths;
        $url = explode('/', $this->url);

        if (!isset($_SESSION[SESS_KEY])){
            if (isset($routePublicPaths[$this->url])) {
                $this->controller = $routePublicPaths[$this->url]['controller'];
                $this->method = $routePublicPaths[$this->url]['method'];
            } else {
                $this->controller = $routePublicPaths['/404']['controller'];
                $this->method = $routePublicPaths['/404']['method'];
            }
            $_SESSION[CONTROLLER_GROUP] = 'Public';
        } else {
            $this->controller = (!empty($url[1]) ? $url[1] : $_SESSION[CONTROLLER_GROUP]) . 'Controller';
            $this->method = !empty($url[2]) ? $url[2] : 'index';
        }

        // if(!is_file(CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php"))
		// {
        //     $_SESSION[CONTROLLER_GROUP] = 'Public';
        //     $this->controller = 'PageController';
        //     $this->method = 'error404';
		// }
    }

    public function run()
    {
        try {
            $database = new Database();

            require_once CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php";
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
