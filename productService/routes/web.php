<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'v1/product', 'namespace' => 'v1', 'middleware' => 'token_check'], function () use ($router) {
    $router->group(['middleware' => 'admin_check'], function () use ($router) {
        $router->post('/product', 'ProductController@store');
    });
});
