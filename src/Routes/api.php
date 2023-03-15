<?php


use Illuminate\Support\Facades\Route;
use Slait\RestfulApi\Http\Controllers\RestfulController;

$prefix = !empty(config('restfulapi.prefix'))? config('restfulapi.prefix') : 'general';
$middlewares = config('restfulapi.middlewares');

//$controlledMiddlewares = ['api'];
//if (count($middlewares) > 0) {
//    foreach ($middlewares as $middleware) {
//        if ($middleware != 'api') {
//            array_push($controlledMiddlewares, $middleware);
//        }
//    }
//}

$authMiddleware = ['api','auth:sanctum'];
$customMiddleware = ['api'];
if (count($middlewares) > 0) {
    foreach ($middlewares as $middleware) {
        if ($middleware != 'auth:sanctum' || $middleware != 'api') {
            array_push($authMiddleware, $middleware);
            array_push($customMiddleware, $middleware);
        }
    }
}

Route::group(['prefix' => 'api'], function() use($prefix, $customMiddleware, $authMiddleware){
    Route::group(['prefix' => $prefix], function () use($customMiddleware, $authMiddleware) {
        Route::get('{endpoint}', [RestfulController::class, 'index'])->middleware($customMiddleware);
        Route::post('{endpoint}', [RestfulController::class, 'store'])->middleware($authMiddleware);
        Route::get('{endpoint}/{id}', [RestfulController::class, 'show'])->middleware($customMiddleware);
        Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [RestfulController::class, 'update'])->middleware($authMiddleware);
        Route::delete('{endpoint}/{id}', [RestfulController::class, 'destroy'])->middleware($authMiddleware);
    });
});
