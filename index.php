<?php
$SERVER_START = true;

require_once './config/parameters.php'; // Importamos los parametros de ejecución
require_once './libs/PHPRouting/Server.php'; // Importamos la libreria del Servidor
require_once './controller/error.controller.php'; // Importamos el controlador de errores

$app = new Server(); // Creamos como tal las funcionalidades del servidor

$app->setRenderingConfig(
    $app->IRenderConfig(
        ServerOptions: [
            "headerServer" => [],
        ],
        HTMLOptions: [
            "headerHtml" => [
                "type" => "import",
                "html" => "./views/partials/header.php",
                "routes" => ['FULL'],
            ],
            "posHeaderHtml" => [
                "type" => "text",
                "html" => '<h2>NavBar</h2>',
                "routes" => ['FULL'],
            ],
            "bodyHtml" => [],
            "footerHtml" => [
                "type" => "import",
                "html" => "./views/partials/footer.php",
                "routes" => ['FULL'],
            ],
        ],
    )
);

// Importamos el archivo con todas las rutas
require_once './Router.php';

?>

<?php

$app->endPoint();

?>