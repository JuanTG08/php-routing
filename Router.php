<?php
if (!isset($SERVER_START)) header('Location:/');
// Requerimos los controladores para la funcionalidad
require_once './controller/public.controller.php';

// Establecemos las rutas que necesitamos mas sus funciones, a su vez es posible establecer que method podemos pedir
$app->setRoutes(
    $app->IRoutes(
        path: '/',
        callback: 'PublicController::home',
        method: 'GET',
    ),
    $app->IRoutes('/about-us', 'PublicController::about_us', 'GET'),
    $app->IRoutes('/user/:id', 'PublicController::user', 'GET'),
    $app->IRoutes('/user/:id/:uwu', 'PublicController::kevin', 'POST'),

    $app->IRoutes('/error403', 'ErrorController::error403', 'FULL'),
    $app->IRoutes('/error404', 'ErrorController::error404', 'FULL'),

    // Error 404 Not Found
    $app->IRoutes(
        path: '*',
        callback: null,
        redirecTo: '/error404'
    ),
);
