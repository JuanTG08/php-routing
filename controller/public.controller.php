<?php

class PublicController {
    public static function home() {
        require_once './views/partials/header.php';

        require_once './views/public/home.php';

        require_once './views/partials/footer.php';
    }

    public static function about_us() {
        require_once './views/partials/header.php';

        require_once './views/public/about_us.php';

        require_once './views/partials/footer.php';
    }

    public static function user() {
        echo "User";
    }

    public static function kevin() {
        echo "Shi";
    }
}