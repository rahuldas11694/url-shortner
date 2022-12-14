<?php

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
    if(Auth::check()){ // if logged in then redirect to /home
        return redirect()->route('home');
    }
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\ShortUrlController::class, 'index'])->name('home');

    Route::post('/generate-short-url', [App\Http\Controllers\ShortUrlController::class, 'generate']);

    Route::get('/list', [App\Http\Controllers\ShortUrlController::class, 'show']);

    Route::get('/plans', [App\Http\Controllers\PlansController::class, 'show']);

    Route::post('/plan/upgrade', [App\Http\Controllers\PlansController::class, 'upgrade']);

    Route::post('/short-url/update', [App\Http\Controllers\ShortUrlController::class, 'update']);

    Route::get('/short-url/edit/{id}', [App\Http\Controllers\ShortUrlController::class, 'edit']);

    Route::post('/short-url/edit/{id}', [App\Http\Controllers\ShortUrlController::class, 'edit']);

});




