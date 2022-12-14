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
    if(Auth::check()){ // if logged in the redirect to /home
        // return redirect()->route('home');
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\ShortUrlController::class, 'index'])->name('home');

Route::post('/generate-short-url', [App\Http\Controllers\ShortUrlController::class, 'generate']);

Route::get('/list', [App\Http\Controllers\ShortUrlController::class, 'show']);
