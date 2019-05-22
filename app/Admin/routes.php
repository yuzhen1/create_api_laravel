<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('register/show', 'RegUserController@show');
    $router->get('register/yes', 'RegUserController@yes'); //通过
    $router->get('register/no', 'RegUserController@no');  //驳回
});
