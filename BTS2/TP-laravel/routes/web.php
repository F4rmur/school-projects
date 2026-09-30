<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccueilController::class, 'index'])->name(name: 'accueil');
Route::post('/language', [LanguageController::class, 'update'])->name('language.update');

Route::middleware('auth')->group(function () {
    Route::redirect('/home', '/user');
    Route::redirect('/dashboard', '/user');

    Route::post('absence/{numeroAbsence}/approve', [AbsenceController::class, 'approve'])
        ->middleware('can:manage-all-absences')
        ->name('absence.approve');

    Route::resource('absence', AbsenceController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->parameters(['absence' => 'numeroAbsence']);
    Route::resource('user', UsersController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update'])
        ->parameters(['user' => 'idUser']);

    Route::middleware('can:manage-roles')->group(function () {
        Route::resource('roles', RolesController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);
    });
});
