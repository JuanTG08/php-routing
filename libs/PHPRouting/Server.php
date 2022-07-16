<?php
require_once './libs/PHPRouting/Controller/ErrorController.php';

class Server extends ErrorController {
    private $URI;
    private $METHOD;
    private $Routes;

    public function __construct() {
        $this->URI = $_SERVER['REQUEST_URI'];
        $this->METHOD = $_SERVER['REQUEST_METHOD'];
    }

    public function IRoutes(string $path, string $callback, string $method = 'GET'): array {
        return [
            "Path" => $path,
            "Callback" => $callback,
            "Method" => $method
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

                $haveParams = $this->verifyPathUri($this->descomposeUri($this->URI), $this->descomposeUri($route['Path']));

                // Si coinciden con su metodo, esta ejecutara su callback
                if (/* $route['Path'] === $this->URI */ $haveParams && $route['Method'] === $this->METHOD) {
                    return $route['Callback']();
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
    private function descomposeUri(string $uri): array {
        return explode('/', $uri);
    }

    private function verifyPathUri(array $pathGiven, array $pathRequired) {
        if (count($pathRequired) != count($pathGiven)) return false;
        $params = [];
        foreach ($pathRequired as $index => $path) {
            // if ($pathGiven[$index] === '' && $index > 0) return false;
            if (!in_array($path, $pathGiven)) {
                echo "aca";
                if (strpos($path, ':') === false) return false;
                // echo "-".$pathGiven[$index]."-";
                array_push($params, array(explode(':', $path)[1] => $pathGiven[$index]));
            }
        }
        return $params !== [] ? $params : true;
    }

    private function descomposeParams(array $params): array {
        $paramsEnd = [];
        foreach ($params as $key => $path) {
            if (strpos($path, ':') !== false) {
                array_push($paramsEnd, explode(':', $path)[1]);
            }
        }
        var_dump($paramsEnd);
        return [];
    }
}