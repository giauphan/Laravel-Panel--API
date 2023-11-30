<?php

use App\Http\Controllers\Api\FileDestroy;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\FileViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api', 'scopes:place-file-upload'])->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::middleware('throttle:35,1')->prefix('file')->group(function () {
        Route::get('show', [FileViewController::class, '__invoke']);
        Route::post('upload', [FileUploadController::class, 'index']);
        Route::post('update', [FileUploadController::class, 'index']);
        Route::delete('delete', [FileDestroy::class, '__invoke']);
    });
});
