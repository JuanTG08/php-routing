<?php
require_once './config/parameters.php';
require_once './libs/PHPRouting/Server.php';
require_once './controller/public.controller.php';

$app = new Server(); // Creamos como tal las funcionalidades del servidor

// Importamos el archivo con todas las rutas
require_once './Router.php';

?>

<?php

$app->endPoint();

?>