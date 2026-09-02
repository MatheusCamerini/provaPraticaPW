<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmeController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/filmes');

Route::prefix('user')->group(function () {
    Route::get('login', [UserController::class, 'showLoginForm'])->name('login')->middleware('guest');
    Route::get('registro', [UserController::class, 'showRegisterForm'])->name('registro')->middleware('guest');
    Route::get('usuario', [UserController::class, 'profile'])->name('usuario')->middleware('auth');

    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::post('registro', [UserController::class, 'register'])->name('user.register');
    Route::post('logout', [UserController::class, 'logout'])->name('user.logout')->middleware('auth');
});

Route::prefix('filmes')->group(function () {
    Route::get('/', [FilmeController::class, 'index'])->name('filmes.index');
    Route::get('/search', [FilmeController::class, 'search'])->name('filmes.search');
    Route::get('/adicionar', [FilmeController::class, 'create'])->name('filmes.create')->middleware('auth');
    Route::get('/lixeira', [FilmeController::class, 'trash'])->name('filmes.trash')->middleware('auth');
    Route::get('/editar/{id}', [FilmeController::class, 'editForm'])->name('filmes.editForm')->middleware('auth');
    Route::get('/{id}', [FilmeController::class, 'show'])->name('filmes.show');

    Route::post('/', [FilmeController::class, 'store'])->name('filmes.store')->middleware('auth');
    Route::put('/{id}/editar', [FilmeController::class, 'edit'])->name('filmes.edit')->middleware('auth');
    Route::patch('/{id}/restaurar', [FilmeController::class, 'restore'])->name('filmes.restore')->middleware('auth');
    Route::delete('/{id}', [FilmeController::class, 'destroy'])->name('filmes.destroy')->middleware('auth');
    Route::delete('/{id}/forcar', [FilmeController::class, 'forceDelete'])->name('filmes.forceDelete')->middleware('auth');
});