<?php

class Router
{
    protected $routes = [];
    protected $middleware = [];
    protected $currentGroupPrefix = '';
    protected $namedRoutes = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $uri = trim($this->currentGroupPrefix . '/' . trim($uri, '/'), '/');
        $this->routes[$method][$uri] = $action;
    }

    public function group($options, $callback)
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $this->currentGroupPrefix .= '/' . trim($options['prefix'] ?? '', '/');
        call_user_func($callback, $this);
        $this->currentGroupPrefix = $previousGroupPrefix;
    }

    public function middleware($middleware, $action)
    {
        $this->middleware[$action] = $middleware;
    }

    public function name($name, $action)
    {
        $this->namedRoutes[$name] = $action;
    }

    public function route($name, $params = [])
    {
        foreach ($this->namedRoutes as $key => $act) {
            if ($key === $name) {
                $uri = array_search($act, $this->routes['GET'] + $this->routes['POST']);
                if (strpos($uri, '{') !== false) {
                    foreach ($params as $param) {
                        $uri = preg_replace('/\{[^}]+\}/', $param, $uri, 1);
                    }
                }
                return '/' . $uri;
            }
        }
        return null;
    }

    public function dispatch($method, $uri)
    {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $action) {
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([a-zA-Z0-9-_]+)', $route);
            if (preg_match("#^{$pattern}$#", $uri, $matches)) {
                array_shift($matches);
                if (isset($this->middleware[$action])) {
                    $middleware = $this->middleware[$action];
                    require_once "middleware/{$middleware}.php";
                    $middlewareInstance = new $middleware;
                    $middlewareInstance->handle();
                }
                return $this->callAction($action, $matches);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    protected function callAction($action, $params)
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        list($controller, $method) = explode('@', $action);
        require_once "controllers/{$controller}.php";
        $controller = new $controller;
        return call_user_func_array([$controller, $method], $params);
    }
}
