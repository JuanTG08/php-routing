<?php
// if (!isset($SERVER_START)) header('Location:'.base_url);

class ErrorController {
    public static function error404($req) {
        echo 'error404';
    }
    public static function error403($req) {
        echo 'error403';
    }
}