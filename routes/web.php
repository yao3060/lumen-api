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
$router->get('/', 'RootController@index' );

$router->get('/key', function() {
    return str_random(32);
});

$router->get('api', 'RootController@json' );

$router->group(['prefix' => 'api/jwt-auth/v1'], function () use ($router) {

    $router->post('token', 'AuthController@token');

    $router->get('user/profile', function () {
        // Uses Auth Middleware
        return response()->json($_SERVER);
    });

});

$router->group(['prefix' => 'api/wp/v1/', 'middleware' => 'auth'], function () use ($router) {

    $router->get('posts', 'PostsController@list');

    $router->get('user/profile', function () {
        // Uses Auth Middleware
        return response()->json($_SERVER);
    });

});
