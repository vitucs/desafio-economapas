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
    return view('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/home', [App\Http\Controllers\HomeController::class, 'create'])->name('createGroup');

Route::get('/groups', [App\Http\Controllers\HomeController::class, 'groups'])->name('groups');

Route::get('/edit/{id}', [App\Http\Controllers\HomeController::class, 'editGroup'])->name('editGroup');

Route::patch('/update/{id}', [App\Http\Controllers\HomeController::class, 'updateGroup'])->name('updateGroup');

Route::delete('/delete/{id}', [App\Http\Controllers\HomeController::class, 'delete'])->name('delete');
