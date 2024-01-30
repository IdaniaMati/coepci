<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmpleadoLoginController;
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

/*Route::get('/', function () {
    return view('welcome');
});*/

/*-----------------------
* login de empleado
*/
Route::get('/empleado/login', [EmpleadoLoginController::class, 'showLoginForm'])->name('empleado.login');
Route::post('/empleado/login', [EmpleadoLoginController::class, 'login'])->name('empleado.login.submit');/*

* login de empleado
------------------------*/


/*------------------------
* Formulario de empleado
*/
//Route::get('/formulario', [EmpleadoLoginController::class, 'mostrarFormulario'])->name('empleado.formulario');

Route::middleware(['auth:empleado'])->group(function () {
    Route::get('/empleado/formulario', [EmpleadoLoginController::class, 'mostrarFormulario'])->name('empleado.formulario');
});
/*
* Formulario de empleado
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

Route::get('/historial', function () {
    return view('historial');
})->middleware(['auth', 'verified'])->name('historial');
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
