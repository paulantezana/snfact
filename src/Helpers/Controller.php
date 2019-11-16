<?php

class Controller
{
    protected function render($path, $params = []){
        extract($params);
        require_once VIEW_PATH . '/' . $path;
    }
    protected function redirect($url = ""){
        header('Location: ' . URL_PATH . $url);
    }
    protected function getParsedBody($assoc = true){
        return json_decode(file_get_contents('php://input'), $assoc);
    }
}