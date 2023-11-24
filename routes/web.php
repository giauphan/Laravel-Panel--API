<?php

use App\Http\Controllers\Public\PreviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return [];
})->name('home');
Route::get('/login', function () {
    return ['login'=>"token Expire"];
})->name('login');

Route::get('/preview', [PreviewController::class, 'index'])->name('preview');
