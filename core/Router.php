<?php

namespace Core;

class Router
{
    /**
     * Lista de rutas registradas.
     */
    private array $routes = [];


    // =====================================================
    // MÉTODOS HTTP
    // =====================================================

    public function get(
        string $route,
        callable $action
    ): void {
        $this->add(
            'GET',
            $route,
            $action
        );
    }


    public function post(
        string $route,
        callable $action
    ): void {
        $this->add(
            'POST',
            $route,
            $action
        );
    }


    public function put(
        string $route,
        callable $action
    ): void {
        $this->add(
            'PUT',
            $route,
            $action
        );
    }


    public function delete(
        string $route,
        callable $action
    ): void {
        $this->add(
            'DELETE',
            $route,
            $action
        );
    }


    // =====================================================
    // REGISTRAR RUTA
    // =====================================================

    private function add(
        string $method,
        string $route,
        callable $action
    ): void {

        /*
         * Normalizamos la ruta.
         *
         * /api/productos/
         *
         * pasa a:
         *
         * /api/productos
         */

        if ($route !== '/') {
            $route = rtrim($route, '/');
        }


        $this->routes[] = [

            'method' => strtoupper($method),

            'route' => $route,

            'action' => $action

        ];
    }


    // =====================================================
    // PROCESAR PETICIÓN
    // =====================================================

    public function dispatch(
        string $method,
        string $uri
    ): void {

        /*
         * Normalizar método HTTP
         */

        $method = strtoupper($method);


        /*
         * Obtener solamente el PATH.
         *
         * Ejemplo:
         *
         * /api/productos?estado=1
         *
         * se transforma en:
         *
         * /api/productos
         */

        $uri = parse_url(
            $uri,
            PHP_URL_PATH
        ) ?? '/';


        /*
         * Decodificar URL
         */

        $uri = rawurldecode($uri);


        /*
         * Asegurar "/" inicial.
         */

        if (!str_starts_with($uri, '/')) {

            $uri = '/' . $uri;
        }


        /*
         * Eliminar "/" final.
         */

        if ($uri !== '/') {

            $uri = rtrim(
                $uri,
                '/'
            );
        }


        // =================================================
        // BUSCAR RUTA
        // =================================================

        foreach ($this->routes as $route) {


            /*
             * Convertimos los parámetros dinámicos.
             *
             * Ejemplo:
             *
             * /api/productos/{id}
             *
             * pasa a:
             *
             * /api/productos/(\d+)
             */


            $pattern = preg_quote(
                $route['route'],
                '#'
            );


            /*
             * Como preg_quote también protege:
             *
             * { }
             *
             * debemos sustituir:
             *
             * \{id\}
             *
             * por:
             *
             * (\d+)
             */

            $pattern = str_replace(
                '\{id\}',
                '(\d+)',
                $pattern
            );


            /*
             * Crear expresión regular final.
             */

            $pattern =
                '#^' .
                $pattern .
                '$#';


            /*
             * Verificar si la URI coincide.
             */

            if (
                preg_match(
                    $pattern,
                    $uri,
                    $matches
                )
            ) {

                /*
                 * Encontramos la URL.
                 *
                 * Ahora verificamos que también
                 * coincida el método HTTP.
                 */

                if (
                    $route['method'] !== $method
                ) {

                    continue;
                }


                /*
                 * Quitar coincidencia completa.
                 *
                 * Ejemplo:
                 *
                 * [
                 *    "/api/productos/5",
                 *    "5"
                 * ]
                 *
                 * pasa a:
                 *
                 * [
                 *    "5"
                 * ]
                 */

                array_shift($matches);


                /*
                 * Ejecutar Controller.
                 *
                 * Ejemplo:
                 *
                 * ProductoController->show(5)
                 */

                call_user_func_array(
                    $route['action'],
                    $matches
                );


                return;
            }
        }


        // =================================================
        // RUTA NO ENCONTRADA
        // =================================================

        Response::error(
            'Ruta no encontrada',
            404
        );
    }
}