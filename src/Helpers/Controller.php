<?php

class Controller
{
    public function render($path, $params = []){
        extract($params);
        require_once VIEW_PATH . '/' . $path;
    }
    public function redirect($url = ""){
        header('Location: ' . URL_PATH . $url);
    }
    public function getParsedBody($assoc = true){
        return json_decode(file_get_contents('php://input'), $assoc);
    }
}