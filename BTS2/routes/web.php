<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ChaiseController;
use App\Http\Controllers\MathematiqueController;
use App\Http\Controllers\MotifController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccueilController::class, 'index'])->name(name: 'accueil');

Route::middleware('auth')->group(function () {
    Route::redirect('/home', '/user');
    Route::redirect('/dashboard', '/user');
    Route::get('/page/{page?}', [AccueilController::class, 'page'])->name(name: 'page');
    Route::get('/title/{title?}', [AccueilController::class, 'title'])->name(name: 'title');

    Route::get('/math/addition/{a}/{b}', [MathematiqueController::class, 'addition'])->name(name: 'addition');
    Route::get('/math/soustraction/{a}/{b}', [MathematiqueController::class, 'soustraction'])->name(name: 'soustraction');
    Route::get('/math/multiplication/{a}/{b}', [MathematiqueController::class, 'multiplication'])->name(name: 'multiplication');
    Route::get('/math/division/{a}/{b}', [MathematiqueController::class, 'division'])->name(name: 'division');

    Route::resource('test', TestController::class);
    Route::resource('chaise', ChaiseController::class);
    Route::resource('motif', MotifController::class);

    Route::resource('absence', AbsenceController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->parameters(['absence' => 'numeroAbsence']);
    Route::resource('user', UsersController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['user' => 'idUser']);
});
