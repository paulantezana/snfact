<?php

class Router
{
    public $controller;
    public $method;

    public function __construct()
    {
        $this->matchRoute();
    }

    private function matchRoute()
    {
        $url = explode('/', URL);

        if (!isset($_SESSION[SESS_KEY])) {
            $_SESSION[CONTROLLER_GROUP] = 'Public';
            $this->controller = (!empty($url[1]) ? $url[1] : 'Page') . 'Controller';
            $this->method = !empty($url[2]) ? $url[2] : 'index';
            if (!is_file(CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php")) {
                $this->controller = 'PageController';
                $this->method = 'error404';
            }
        } else {
            if($_SESSION[CONTROLLER_GROUP] === 'Manager'){
                $this->controller = (!empty($url[1]) ? $url[1] : 'Manager') . 'Controller';
                $this->method = !empty($url[2]) ? $url[2] : 'index';
                if (!is_file(CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php")) {
                    $this->controller = 'ManagerController';
                    $this->method = 'error404';
                }
            }elseif($_SESSION[CONTROLLER_GROUP] === 'Company'){
                $this->controller = (!empty($url[1]) ? $url[1] : 'Company') . 'Controller';
                $this->method = !empty($url[2]) ? $url[2] : 'index';
                if (!is_file(CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php")) {
                    $this->controller = 'CompanyController';
                    $this->method = 'error404';
                }
            } else{
                $this->controller = 'PageController';
                $this->method = 'error404';
            }
        }
    }

    public function run()
    {
        try {
            require_once CONTROLLER_PATH . "/{$_SESSION[CONTROLLER_GROUP]}/{$this->controller}.php";
            if(!method_exists($this->controller,$this->method)){
                $this->method = 'index';
            }

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
        $error = 'PHP Fatal error | URL : ' . URL . "\n" . 'IP : ' . $ipClient . ' | ' . $ipProxy . ' | ' . $ipServer . "\n" . ' ERROR index : ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
        error_log($error);
    }
}
