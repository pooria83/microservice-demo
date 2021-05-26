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
// $router->group(['middleware' => 'client.credentials'], function() use ($router){
    $router->group(['prefix' => 'v1' , 'namespace' => 'v1'] , function() use ($router) {
        $router->post('/login' , 'UserController@login');
        $router->post('/register' , 'UserController@register');
    });

// });
