<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Artisan;

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
$router->group(['prefix' => 'v1', 'namespace' => 'v1'], function () use ($router) {
    //User Route
    $router->post('/login', 'UserController@login');
    $router->post('/register', 'UserController@register');

    //Product Routes
    $router->post('/public_link', 'ProductController@public_link');
    $router->post('/submit', 'ProductController@store');
    $router->get('/get_list', 'ProductController@list');
    $router->get('/check_view', 'Product_UserController@check_view_count');
    $router->post('/edit', 'ProductController@edit');
    $router->post('/delete', 'ProductController@delete');
    $router->post('/product', 'ProductController@single');

});

// });
