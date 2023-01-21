<?php


use Illuminate\Support\Facades\Route;
use Slait\RestfulApi\Http\Controllers\RestfulController;

$prefix = !empty(config('restfulapi.prefix'))? config('restfulapi.prefix') : 'general';
$middlewares = config('restfulapi.middlewares');
$controlledMiddlewares = ['api'];
if (count($middlewares) > 0) {
    foreach ($middlewares as $middleware) {
        if ($middleware != 'api') {
            array_push($controlledMiddlewares, $middleware);
        }
    }
}

Route::group(['prefix' => 'api', 'middleware' => $controlledMiddlewares], function() use($prefix){
    Route::group(['prefix' => $prefix], function () {
        Route::get('{endpoint}', [RestfulController::class, 'index']);
        Route::post('{endpoint}', [RestfulController::class, 'store']);
        Route::get('{endpoint}/{id}', [RestfulController::class, 'show']);
        Route::match(['put', 'patch'],'{endpoint}/{id}', [RestfulController::class, 'update']);
        Route::delete('{endpoint}/{id}', [RestfulController::class, 'destroy']);
    });
});
