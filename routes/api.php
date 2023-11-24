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
    Route::middleware('throttle:10,1')->group(['as' => 'passport.', 'prefix' => 'file'], function () {
        Route::get('show', [FileViewController::class, '__invoke']);
        Route::post('upload', [FileUploadController::class, 'index']);
        Route::delete('delete', [FileDestroy::class, '__invoke']);
    });
});
