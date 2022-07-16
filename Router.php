<?php
// Requerimos los controladores para la funcionalidad
require_once './controller/public.controller.php';

// Establecemos las rutas que necesitamos mas sus funciones, a su vez es posible establecer que method podemos pedir
$app->setRoutes(
    $app->IRoutes('/', 'PublicController::home', 'GET'),
    $app->IRoutes('/about-us', 'PublicController::about_us','GET'),
    $app->IRoutes('/user/:id', 'PublicController::user', 'GET'),
);