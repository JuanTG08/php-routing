<?php
require_once './libs/PHPRouting/Controller/ErrorController.php';

class Server extends ErrorServerController {
    private $URI;
    private $URL_HOST;
    private $METHOD;
    private $Routes;

    public function __construct() {
        $this->URI = $_SERVER['REQUEST_URI'];
        $this->METHOD = $_SERVER['REQUEST_METHOD'];
        $this->URL_HOST = $_SERVER['HTTP_HOST'];
    }

    public function IRoutes(
        string $path,
        string | null $callback = null,
        string $method = 'GET',
        string | bool $redirecTo = false,
    ): array {
        return [
            "Path" => $path,
            "Callback" => $callback,
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

                if ($route['Path'] === '*' && $route['redirecTo'] !== false) header('Location:'.$route['redirecTo']);

                $Params = $this->verifyPathUri($this->descomposeUri($this->URI), $this->descomposeUri($route['Path']));
                // Si coinciden con su metodo, esta ejecutara su callback
                if ($Params && ($route['Method'] === $this->METHOD || $route['Method'] === 'FULL')) {
                    $request = array(
                        [
                            "URI" => [
                                "params" => $Params[0],
                            ]
                        ]
                    );
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
        /*
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
        */
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