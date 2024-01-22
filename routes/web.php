<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*-----------------------
* login de votante
*/

/*
* login de votante
------------------------*/


/*------------------------
* Formulario de votación
*/

/*
* Formulario de votación
------------------------*/


/*-----------------------
* Dashboard
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/datos', function () {
    return view('datos');
})->middleware(['auth', 'verified'])->name('datos');

Route::get('/documentacion', function () {
    return view('documentacion');
})->middleware(['auth', 'verified'])->name('documentacion');
/*
* Dashboard
-----------------------*/


/*----------------------
* Login administrador
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*
* Login administrador
----------------------*/

require __DIR__.'/auth.php';
