<?php
// if (!isset($SERVER_START)) header('Location:'.base_url);
// Requerimos los controladores para la funcionalidad
require_once './controller/public.controller.php';

// Establecemos las rutas que necesitamos mas sus funciones, a su vez es posible establecer que method podemos pedir
$app->setRoutes(
    $app->IRoutes(
        path: '/',
        loadChildren: [
            "import" => './controller/public.controller.php',
            "className" => 'PublicController',
            "functionExecuted" => 'home',
        ],
        method: 'GET',
    ),
    $app->IRoutes(
        path: '/about-us',
        loadChildren: [
            "import" => './controller/public.controller.php',
            "className" => 'PublicController',
            "functionExecuted" => 'about_us',
        ],
        method: 'GET',
    ),

    $app->IRoutes(
        path: '/error403',
        callback: 'ErrorController::error403',
        method: 'FULL'
    ),
    $app->IRoutes(
        path: '/error404',
        callback: 'ErrorController::error404',
        method: 'FULL'
    ),

    // Error 404 Not Found
    $app->IRoutes(
        path: '*',
        redirecTo: '/error404'
    ),
);
