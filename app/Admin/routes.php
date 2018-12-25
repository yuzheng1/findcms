<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('findnotice', FindNoticeController::class);
    $router->group([
        "prefix" => config("admin.route.api.prefix"),
        "namespace" => config("admin.route.api.namespace")
    ], function (Router $router) {
        $router->get("area", "AreaController@index");
    });

});