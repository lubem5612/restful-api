<?php


use Illuminate\Support\Facades\Route;
use Slait\RestfulApi\Http\Controllers\RestfulController;

$prefix = !empty(config('restfulapi.prefix'))? config('restfulapi.prefix') : 'general';
$middlewares = config('restfulapi.middlewares');

$authMiddleware = ['api', 'auth:sanctum'];
$customMiddleware = ['api'];
if (count($middlewares) > 0) {
    foreach ($middlewares as $middleware) {
        if ($middleware != 'auth:sanctum' || $middleware != 'api') {
            array_push($authMiddleware, $middleware);
            array_push($customMiddleware, $middleware);
        }
    }
}

Route::group(['prefix' => 'api', 'middleware' => $customMiddleware], function() use($prefix){
    Route::group(['prefix' => $prefix], function () {
        Route::get('{endpoint}', [RestfulController::class, 'index']);
        Route::get('{endpoint}/{id}', [RestfulController::class, 'show']);
    });
});

Route::group(['prefix' => 'api', 'middleware' => $authMiddleware], function() use($prefix){
    Route::group(['prefix' => $prefix], function () {
        Route::post('{endpoint}', [RestfulController::class, 'store']);
        Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [RestfulController::class, 'update']);
        Route::delete('{endpoint}/{id}', [RestfulController::class, 'destroy']);
    });
});
