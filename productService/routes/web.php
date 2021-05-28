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

$router->group(['prefix' => 'v1/product', 'namespace' => 'v1'], function () use ($router) {
    $router->group(['middleware' => 'token_check'], function () use ($router) {
        $router->get('/public_link', 'ProductController@public_link');
        $router->group(['middleware' => 'admin_check'], function () use ($router) {
            $router->post('/submit', 'ProductController@store');
            $router->get('/get_list', 'ProductController@list');
            $router->post('/single' , 'ProductController@single');
            $router->get('/check_view', 'Product_UserController@check_view_count');
            $router->group(['middleware' => 'update_and_delete_check'], function () use ($router) {
                $router->post('/edit', 'ProductController@edit');
                $router->post('/delete', 'ProductController@delete');
            });
        });
        $router->post('/product', ['middleware' => 'show_product', 'uses' => 'ProductController@single']);
    });
});
