<?php

namespace Zhchenxin\Upload;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        Route::group([
            'namespace' => 'Zhchenxin\Upload\Controllers',
            'prefix' => Config::get('upload.route_prefix'),
            'domain' => Config::get('upload.route_domain'),
            'middleware' => [],
        ], function($router) {
            $router->post('/upload', 'UploadController@upload');
            $router->get('/c/{day}/{time}/{file}', 'UploadController@show');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/upload.php', 'upload');
    }
}