<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Agent;
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
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('bonus/{id?}', [BonusController::class, 'calculateBonus'])->name('admin.calculate.bonus');
    Route::get('pdf', [BonusController::class, 'printPDF'])->name('bonus.pdf');
    Route::post('mark-payment', [BonusController::class, 'markPayment'])->name('bonus.mark.payment');
    Route::get('delete-dbs', [AdminController::class, 'deleteDBS'])->name('delete.dbs');
    Route::post('delete-dbs', [AdminController::class, 'postdeleteDBS'])->name('delete.dbs');
    Route::get('upload-registration', [AdminController::class, 'uploadRegistration'])->name('upload.registration');
    Route::post('upload-registration', [AdminController::class, 'postuploadRegistration'])->name('upload.registration');
    Route::get('upload-achivement', [AdminController::class, 'uploadAchivement'])->name('upload.achivement');
    Route::post('upload-achivement', [AdminController::class, 'postuploadAchivement'])->name('upload.achivement');
    Route::get('reload-statistics', [AdminController::class, 'reloadStatistics'])->name('reload.statistics');
    Route::get('test-data', [AdminController::class, 'testData']);
    Route::get('chain-data', [AdminController::class, 'chainJobs'])->name('chain.data');
});
Route::prefix('admin/agents')->group(function () {
    Route::get('', [AgentController::class, 'index'])->name('admin.agents');
    Route::get('add', [AgentController::class, 'add'])->name('admin.agent.add');
    Route::post('add', [AgentController::class, 'post'])->name('admin.agent.add');
    Route::get('edit/{id}', [AgentController::class, 'edit'])->name('admin.agent.edit');
    Route::post('edit/{id}', [AgentController::class, 'update'])->name('admin.agent.edit');
    Route::post('adjust-pvb/{id}', [AgentController::class, 'adjustPvb'])->name('admin.agent.adjust.pvb');
    Route::get('make-payments/{id}', [AgentController::class, 'makePayment'])->name('admin.agent.payment');
    Route::get('export-r', [ExportController::class, 'exportAR'])->name('admin.agent.export.ar');
    Route::get('export-a', [ExportController::class, 'exportAA'])->name('admin.agent.export.aa');
    Route::post('upload-export-r', [ExportController::class, 'uploadExportR'])->name('admin.agent.upload.export.r');
    Route::post('upload-export-a', [ExportController::class, 'uploadExportA'])->name('admin.agent.upload.export.a');
    Route::get('print-agent-report', [AgentController::class, 'printReport'])->name('admin.agent.print.report');
});
Route::prefix('admin/users')->group(function () {
    Route::get('', [UserController::class, 'index'])->name('admin.users');
    Route::get('add', [UserController::class, 'add'])->name('admin.user.add');
    Route::post('add', [UserController::class, 'post'])->name('admin.user.add');
    Route::get('configuration', [UserController::class, 'configuration'])->name('admin.user.configuration');
    Route::post('configuration', [UserController::class, 'postConfiguration'])->name('admin.user.configuration');
});
Route::prefix('admin/awards')->group(function () {
    Route::get('', [AwardController::class, 'index'])->name('admin.awards');
    Route::get('add', [AwardController::class, 'add'])->name('admin.awards.add');
    Route::post('add', [AwardController::class, 'post'])->name('admin.awards.add');
});
