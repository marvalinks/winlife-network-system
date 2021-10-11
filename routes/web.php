<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
    Route::get('bonus/{id?}', [BonusController::class, 'calculateBonus'])->name('admin.calculate.bonus');
    Route::get('pdf', [BonusController::class, 'printPDF'])->name('bonus.pdf');
});
Route::prefix('admin/agents')->group(function () {
    Route::get('', [AgentController::class, 'index'])->name('admin.agents');
    Route::get('add', [AgentController::class, 'add'])->name('admin.agent.add');
    Route::post('add', [AgentController::class, 'post'])->name('admin.agent.add');
    Route::get('edit/{id}', [AgentController::class, 'edit'])->name('admin.agent.edit');
    Route::post('adjust-pvb/{id}', [AgentController::class, 'adjustPvb'])->name('admin.agent.adjust.pvb');
    Route::get('make-payments/{id}', [AgentController::class, 'makePayment'])->name('admin.agent.payment');
});
Route::prefix('admin/users')->group(function () {
    Route::get('', [UserController::class, 'index'])->name('admin.users');
    Route::get('add', [UserController::class, 'add'])->name('admin.user.add');
    Route::post('add', [UserController::class, 'post'])->name('admin.user.add');
});
