<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
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


Route::get('', [PagesController::class, 'login'])->name('login');
Route::post('', [PagesController::class, 'postLogin'])->name('login');
Route::get('register', [PagesController::class, 'register'])->name('register');
Route::post('register', [PagesController::class, 'postRegister'])->name('register');

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
Route::prefix('admin/agents')->group(function () {
    Route::get('', [AgentController::class, 'index'])->name('admin.agents');
    Route::get('add', [AgentController::class, 'add'])->name('admin.agent.add');
    Route::post('add', [AgentController::class, 'post'])->name('admin.agent.add');
});
Route::prefix('admin/products')->group(function () {
    // Route::get('', [AgentController::class, 'index'])->name('admin.agents');
    Route::get('add', [ProductController::class, 'add'])->name('admin.product.add');
    // Route::post('add', [AgentController::class, 'post'])->name('admin.agent.add');
});
