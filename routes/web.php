<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['middleware' => 'token'], function () use ($router) {

    $router->get('/', function ()    {
        // Uses Auth Middleware
        return route('/');
    });

    $router->get('home', function () {
        return response()->json(['name' => 'Abigail', 'state' => 'CA']);
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
        return json_encode($_SERVER);
    });

});