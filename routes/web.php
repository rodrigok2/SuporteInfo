<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\OsController;
use App\Http\Controllers\Admin\VipController;
use App\Http\Controllers\Admin\ServicoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AndamentoController;
use App\Http\Controllers\Admin\LogBackupController;
use App\Http\Controllers\Admin\LeadsController;

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

Route::middleware(['auth'])
    ->prefix('admin/dashboard')
    ->name('admin.dashboard.')
    ->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/painel', [DashboardController::class, 'painel'])->name('painel');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/logs')
    ->name('admin.logs.')
    ->group(function(){
        Route::get('/', [LogBackupController::class, 'index'])->name('index');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/leads')
    ->name('admin.leads.')
    ->group(function(){
        Route::get('/', [LeadsController::class, 'index'])->name('index');
        Route::get('/detalhes', [LeadsController::class, 'detalhes'])->name('detalhes');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/vip')
    ->name('admin.vip.')
    ->group(function() {
        Route::get('/', [VipController::class, 'index'])->name('index');
        Route::get('/pesquisar', [VipController::class, 'pesquisar'])->name('pesquisar');
        Route::post('/', [VipController::class, 'cadastrar'])->name('cadastrar');
        Route::delete('/{vip_id}/excluir', [VipController::class, 'excluir'])->name('excluir');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/os/')
    ->name('admin.os.')
    ->group(function(){
        Route::get('/', [OsController::class, 'index'])->name('index');
        Route::get('/{os_id}', [OsController::class, 'detalhes'])->name('detalhes');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/servicos')
    ->name('admin.servicos.')
    ->group(function(){
        Route::get('/', [ServicoController::class, 'index'])->name('index');
        Route::get('/detalhes', [ServicoController::class, 'detalhes'])->name('detalhes');
    }
);

Route::middleware(['auth'])
    ->prefix('admin/andamentos')
    ->name('admin.andamentos.')
    ->group(function() {
        Route::get('/', [AndamentoController::class, 'index'])->name('index');
    }
);

Route::middleware(['auth'])
    ->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    }
);

Route::middleware(['auth'])
    ->group(function(){
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    }
);

Auth::routes();
Route::get('/home', [DashboardController::class, 'index'])->name('index');
Auth::routes();


