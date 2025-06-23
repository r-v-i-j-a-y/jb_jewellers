<?php
require_once 'services/router.php';
require_once 'routes/routes.php';
require_once 'services/helpers.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
function includeWithData($filePath, $data = [])
{
    extract($data);
    include $filePath;
}

$action = $url[1] ?? 'index';

