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

$router->get('api', function ()    {
    // Uses Auth Middleware
    return response()->json($_SERVER);
});

$router->group(['prefix' => 'api/jwt-auth/v1'], function () use ($router) {

    $router->post('token', 'AuthController@token');

    $router->get('user/profile', function () {
        // Uses Auth Middleware
        return response()->json($_SERVER);
    });

});

$router->group(['prefix' => 'api/v1/'], function () use ($router) {

    $router->get('home', function () {
        return response()->json(['name' => 'Abigail', 'state' => 'CA']);
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
        return response()->json($_SERVER);
    });

});
