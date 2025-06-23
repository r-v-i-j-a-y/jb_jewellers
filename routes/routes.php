<?php

require_once __DIR__ . "/../services/router.php";

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@login');


$router->get('/register', 'RegisterController@index');
$router->post('/register', 'RegisterController@store');

$router->get('/user-details', 'UserDetailsController@index');
$router->post('/user-details', 'UserDetailsController@update');

$router->get('/view-users', 'UserDetailsController@allUser');
$router->get('/logout', 'LogoutController@index');

$router->get('/schemes', 'SchemeController@index');
$router->get('/scheme-create', 'SchemeController@create');
$router->post('/scheme-create', 'SchemeController@store');
$router->post('/scheme-status-update', 'SchemeController@status');

$router->get('/chit-list', 'ChitController@index');
$router->post('/chit-status-update', 'ChitController@status');
$router->get('/chit-create', 'ChitController@create');
$router->post('/chit-create', 'ChitController@store');

// $router->get('/about', 'HomeController@about');
// $router->get('/user/{id}', 'HomeController@show');
// $router->post('/user/save', 'HomeController@store');

// // Route with middleware
// $router->middleware('AuthMiddleware', 'HomeController@dashboard');
// $router->get('/dashboard', 'HomeController@dashboard');

// // Named route
// $router->name('user.show', 'HomeController@show');

// // Grouped routes
// $router->group(['prefix' => 'admin'], function ($router) {
//     $router->get('/panel', 'HomeController@adminPanel');
// });
