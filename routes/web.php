<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Crud/Index');
});

Route::get('/ajax-get-users', [UserController::class, 'ajax_get_users'])->name('ajax.users.data');
Route::post('/ajax-get-users', [UserController::class, 'ajax_user_store'])->name('ajax.user.store');
Route::put('/ajax-get-users/{id}/update', [UserController::class, 'ajax_user_update'])->name('ajax.user.update');
Route::delete('/ajax-get-users/{id}/delete', [UserController::class, 'ajax_user_delete'])->name('ajax.user.delete');
