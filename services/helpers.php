<?php
function base_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath = rtrim($scriptDir, '/') . '/';
    return $protocol . $host . $basePath;
}

function asset($path)
{
    return base_url() . 'public/assets/' . ltrim($path, '/');
}
