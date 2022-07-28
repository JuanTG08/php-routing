<?php
require_once './libs/PHPRouting/Controller/ErrorController.php';

class Server extends ErrorServerController {
    private $URI;
    private $URL_HOST;
    private $METHOD;
    private $Routes;

    public function __construct() {
        $this->URI = base_url;
        $this->METHOD = $_SERVER['REQUEST_METHOD'];
        $this->URL_HOST = $_SERVER['HTTP_HOST'];
    }

    public function IRoutes(
        string $path, // Direccion URL la cual se habilitara
        string | bool $callback = false, // Funcion de regreso
        array | bool $loadChildren = false, // Complemento para la ejecucion del loadChildren
        string $method = 'GET', // Metodo el cual se ejecutara
        string | bool $redirecTo = false, // Redireccion
    ): array {
        return [
            "Path" => $path,
            "Callback" => $callback,
            "loadChildren" => $loadChildren,
            "Method" => $method,
            "redirecTo" => $redirecTo,
        ];
    }

    public function setRoutes(...$Routes) {
        $this->Routes = $Routes;
    }

    // Cramos la funcionalidad la cual dara la direccion de la ruta
    public function endPoint() {
        // Si no hay nada rutas establecidas le generamos errorCannotGet
        if (empty($this->Routes)) return $this->errorCannotGet($this->URI);
        try {
            // Seccionamos las rutas para saber cual coincidira
            foreach ($this->Routes as $index => $route) {
                // Realizamos una busqueda de parametros
                // $params = $this->descomposeParams($this->descomposeUri($route['Path']));

                if ($route['Path'] === '*' && $route['redirecTo'] !== false) header('Location:'.$this->URI.$route['redirecTo']);
                $patRouting = empty($_GET['routing']) ? '/' : '/'.$_GET['routing']; // Estructuramos la ruta dada por la variable tipo GET

                $Params = $this->verifyPathUri($this->descomposeUri($patRouting), $this->descomposeUri($route['Path']));
                // Si coinciden con su metodo, esta ejecutara su callback
                if ($Params && ($route['Method'] === $this->METHOD || $route['Method'] === 'FULL')) {
                    $request = array(
                        "url" => [
                            "params" => $Params[0],
                            "url" => $this->URI,
                            "host" => $this->URL_HOST,
                            "method" => $this->METHOD,
                        ],
                    );
                    if ($route['loadChildren']) return $this->executeCallbackForLoadChildren($route['loadChildren'], $request);
                    return $route['Callback']($request);
                }

                // Indicamos que si es el ultimo objeto del array de las rutas, entonces realice las comparaciones necesarias
                if (count($this->Routes) - 1 == $index) {
                    if ($route['Path'] !== $this->URI) return $this->errorNotFound();
                    if ($route['Method'] !== $this->METHOD) return $this->errorNotMethod();
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /*
        Funcionalidades internas para la destructuracion y logica de las rutas
    */
    private function descomposeUri(string $uri, string $indice = '/'): array {
        return explode($indice, $uri);
    }

    private function verifyPathUri(array $pathGiven, array $pathRequired) {
        $params = array();
        foreach ($pathGiven as $i => $path) {
            if (count($pathGiven) != count($pathRequired)) return false;
            if ($path === $pathRequired[$i]) continue;
            if ($path != $pathRequired[$i] && $path != "") {
                $paramsVerify = $this->descomposeUri($pathRequired[$i], ':');
                if (count($paramsVerify) === 1) return false;
                array_push($params, [$paramsVerify[1] => $path]);
                continue;
            } else {
                return false;
            }
        }
        return [$params, true];
    }

    private function executeCallbackForLoadChildren($loadChildren, ...$args) {
        if (!file_exists($loadChildren['import'])) {
            echo 'Failed to load file ('.$loadChildren['import'].'). Please check your configuration or routing configuration.';
            return;
        }
        if (!class_exists($loadChildren['className'])) require_once($loadChildren['import']);
        if (!class_exists($loadChildren['className'])) {
            echo 'Failed to load class ('.$loadChildren['className'].'). Please check your configuration or routing configuration.';
            return;
        }
        $callback = $loadChildren['functionExecuted'];
        try {
            $loadChildren['className']::$callback($args);
        } catch (\Throwable $th) {
            echo 'Failed to load function ('.$loadChildren['functionExecuted'].'). Please check your configuration or routing configuration.';
            return;
        }
        /*
        $errorLoadChildren = array();
        if (!file_exists($loadChildren['import'])) {
            echo 'Failed to load file ('.$loadChildren['import'].'). Please check your configuration or routing configuration.';
            return;
        }
        // echo json_encode($loadChildren); die;
        try {
            if (!class_exists($loadChildren['className'])) include($loadChildren['import']);

            if (!class_exists($loadChildren['className'])) {
                echo 'Failed to load class ('.$loadChildren['className'].'). Please check your configuration or routing configuration.';
                return;
            }
            if (function_exists($loadChildren['functionExecuted'])) {
                echo 'Failed to execute function ('.$loadChildren['functionExecuted'].'). Please check your configuration or routing configuration.';
                return;
            }
            return $loadChildren['className']::$loadChildren['functionExecuted']($args);
        } catch (\Throwable $th) {
            throw $th;
        }
        */
    }
}