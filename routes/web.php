<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmpleadoLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;


/*------------------------* Vista pública ------------------------*/
Route::get('/nominaciones',[EmpleadoLoginController::class, 'nominaciones'])->name('nominaciones');
Route::get('/resultado',[EmpleadoLoginController::class, 'resultado'])->name('resultado');
Route::get('/obtenerResultados', [EmpleadoLoginController::class, 'obtenerResultados'])->name('obtenerResultados');
Route::get('/historico',[EmpleadoLoginController::class, 'historico'])->name('historico');
Route::get('/obtenerOpcionesVotacion/{ronda}', [EmpleadoLoginController::class, 'obtenerOpcionesVotacion'])->name('obtenerOpcionesVotacion');


/*------------------------* login de empleado ------------------------*/
Route::get('/', [EmpleadoLoginController::class, 'showLoginForm'])->name('empleado.login');
Route::post('/EmpleadoLogin', [EmpleadoLoginController::class, 'login'])->name('Empleadologin');
Route::get('/obtenerFechaInicioConcurso', [EmpleadoLoginController::class, 'obtenerFechaInicioConcurso'])->name('obtenerFechaInicioConcurso');

Route::get('/FinVotacion', [EmpleadoLoginController::class, 'FinVotacion'])->name('empleado.FinVotacion');


/*------------------------* Formulario de empleado */
Route::middleware(['auth:empleado'])->group(function () {
    Route::get('/Principal', [EmpleadoLoginController::class, 'Principal'])->name('Principal');
    
    Route::get('/verificarVotoUsuarioActual/{ronda}', [EmpleadoLoginController::class, 'verificarVotoUsuarioActual'])->name('verificarVotoUsuarioActual');
    
    Route::get('/Votacion', [EmpleadoLoginController::class, 'VotacionEmpleado'])->name('VotacionEmpleado');
    
    Route::get('/obtenerOpcionesVotacion/{ronda}', [EmpleadoLoginController::class, 'obtenerOpcionesVotacion'])->name('obtenerOpcionesVotacion');

    Route::get('/obtenerIdUsuarioAutenticado', [EmpleadoLoginController::class, 'obtenerIdUsuarioAutenticado'])->name('obtenerIdUsuarioAutenticado');

    Route::get('/obtenerConcursoId', [EmpleadoLoginController::class, 'obtenerConcursoId'])->name('obtenerConcursoId');
    
    Route::post('/enviarVotacion', [EmpleadoLoginController::class, 'enviarVotacion'])->name('enviarVotacion');
    
    Route::get('/obtenerSegundaFechaConcurso', [EmpleadoLoginController::class, 'obtenerSegundaFechaConcurso'])->name('obtenerSegundaFechaConcurso');
    
    Route::post('/CerrarSesion', [EmpleadoLoginController::class, 'logout'])->name('empleado.logout');

});


/*-----------------------* Dashboard*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/datos', function () {
    return view('datos');
})->middleware(['auth', 'verified'])->name('datos');

Route::get('/historial', function () {
    return view('historial');
})->middleware(['auth', 'verified'])->name('historial');


/*---------------------- * Login administrador */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
