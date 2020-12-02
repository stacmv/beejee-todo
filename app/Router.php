<?php

class Router
{
    private $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;

        // @TODO: add routes validation heere.
    }

    /**
     * Returns "Controller : action" pair for current requert
     *
     * @return array [Controler instanse, method_name]
     */
    public function resolve(App $app): array
    {
        $uri = $this->request_uri();
        $http_method = $this->http_method();

        if (!isset($this->routes[$http_method][$uri])) {
            throw new Exception("404");
        }

        try {
            list($controller_prefix, $action) =  $this->routes[$http_method][$uri];
            $controller_name = '\\Controllers\\' . $controller_prefix . 'Controller';

            if (!class_exists($controller_name)) {
                throw new Exception('Controller class is not exists: "' . $controller_name . '".');
            }
            $controller = new $controller_name($app);

            if (!method_exists($controller, $action)) {
                throw new Exception('Method "' . $controller_name . '@' . $action . '" is not defined.');
            }

        } catch (Exception $e) {
            throw new Exception('Could not initialize controller ' . $controller_name, 0, $e);
        }

        return [$controller, $action];
    }

    public static function protocol()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol;
    }
    public static function baseUrl()
    {
        $protocol = self::protocol();

        $base_url = $protocol . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
        if (substr($base_url, -1) != "/") {
            $base_url .="/";
        }

        return $base_url;

    }

    private function http_method(): string
    {
        // Stub for simplicity
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'get'); // See class Config: HTTP method are defined in lower case
    }

    private function request_uri(): string
    {
        // Compatible with Apache mod_rewrite rule:
        $uri = ! empty($_GET["uri"]) ? $_GET["uri"] : "/";

        $uri = ("index" == $uri ? "/" : $uri);

        if ( ("/" != $uri ) && ("/" == $uri{0}) ){ // @TODOD: rewrite for PHP 8 str_starts_with()
            $uri = substr($uri, 1);
        }

        return $uri;
    }



}
