<?php

class ErrorServerController {
    public function errorCannotGet($uri) {
        require_once './libs/PHPRouting/Screens/CannotGet.php';
    }

    public function errorNotMethod() {
        require_once './libs/PHPRouting/Screens/NotMethod.php';
    }

    public function errorNotFound() {
        require_once './libs/PHPRouting/Screens/NotFound.php';
    }
}