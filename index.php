<?php
function includeWithData($filePath, $data = [])
{
    extract($data);
    include $filePath;
}
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['home', 'index'];

$controllerName = ucfirst($url[0]) . 'Controller';
$action = $url[1] ?? 'index';

// Controller file
$controllerFile = "controllers/$controllerName.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Method '$action' not found.";
    }
} else {
    echo "Controller '$controllerName' not found.";
}
