<?php

class Controller
{
    protected function render($path, $parameter = [], $template = ''){

        $content = RequireToVar(VIEW_PATH . '/' . $path, $parameter);

        if($template === '' || $template === null){
            echo $content;
            return;
        }
        
        require_once(VIEW_PATH . '/' . $template);
    }

    protected function redirect($url = ""){
        header('Location: ' . URL_PATH . $url);
    }
}