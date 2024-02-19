<?php

use App\Http\Controllers\AdminController;
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
Route::get('/obtenerHistorico', [EmpleadoLoginController::class, 'obtenerHistorico'])->name('obtenerHistorico');



/*------------------------* login de empleado ------------------------*/
Route::get('/', [EmpleadoLoginController::class, 'showLoginForm'])->name('empleado.login');
Route::post('/EmpleadoLogin', [EmpleadoLoginController::class, 'login'])->name('Empleadologin');
Route::get('/obtenerFechaInicioConcurso', [EmpleadoLoginController::class, 'obtenerFechaInicioConcurso'])->name('obtenerFechaInicioConcurso');
Route::get('/calcular-y-guardar-ganadores', [EmpleadoLoginController::class, 'calcularYGuardarGanadores'])->name('calcularYGuardarGanadores');
Route::get('/obtenerGanadores', [EmpleadoLoginController::class, 'obtenerGanadores'])->name('obtenerGanadores');


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
Route::get('/dashboard', function () {return view('admin.dashboard');})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/obtenerEmpleados', [AdminController::class, 'obtenerEmpleados'])->name('obtenerEmpleados');
Route::get('/obtenerRegistrosVotos', [AdminController::class, 'obtenerRegistrosVotos']);
Route::get('/obtenerVotosRondas', [AdminController::class, 'obtenerVotosRondas']);

/*-----------------------* Ajustes*/
Route::get('/datos', function () { return view('admin.datos');})->middleware(['auth', 'verified'])->name('datos');

Route::post('/importar-empleados', [AdminController::class, 'importarEmpleados']);
Route::post('/vaciarBaseDatos', [AdminController::class, 'vaciarBaseDatos']);

Route::get('/obtenerEvento', [AdminController::class, 'obtenerEvento']);
Route::post('/agregarEvento', [AdminController::class, 'agregarEvento']);
Route::put('/editarEvento/{id}', [AdminController::class, 'editarEvento']);
Route::delete('/eliminarEvento/{id}', [AdminController::class, 'eliminarEvento']);


Route::get('/historial', function () { return view('admin.historial');})->middleware(['auth', 'verified'])->name('historial');





/*---------------------- * Login administrador */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
