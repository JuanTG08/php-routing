<?php
class PublicController {
    public static function home($req) {
        require_once './views/partials/header.php';

        require_once './views/public/home.php';

        require_once './views/partials/footer.php';
    }

    public static function about_us($req) {
        require_once './views/partials/header.php';

        require_once './views/public/about_us.php';

        require_once './views/partials/footer.php';
    }

    public static function user($req) {
        echo json_encode($_SERVER);
    }

    public static function kevin($req) {
        echo "Shi";
    }
}