<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\EmpleadoLoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;


/*------------------------* Resultados Públicos ------------------------*/
Route::get('/nominaciones',[EmpleadoLoginController::class, 'nominaciones'])->name('nominaciones');
Route::get('/resultado',[EmpleadoLoginController::class, 'resultado'])->name('resultado');
Route::get('/obtenerResultados', [EmpleadoLoginController::class, 'obtenerResultados'])->name('obtenerResultados');
Route::get('/historico',[EmpleadoLoginController::class, 'historico'])->name('historico');
Route::get('/obtenerOpcionesVotacion/{ronda}', [EmpleadoLoginController::class, 'obtenerOpcionesVotacion'])->name('obtenerOpcionesVotacion');
Route::get('/obtenerHistorico', [EmpleadoLoginController::class, 'obtenerHistorico'])->name('obtenerHistorico');
Route::get('/obtenerVotosTodosEmpleados/{idConcurso}', [EmpleadoLoginController::class, 'obtenerVotosTodosEmpleados'])->name('obtenerVotosTodosEmpleados');


/*------------------------* login de empleado Público ------------------------*/
Route::get('/', [EmpleadoLoginController::class, 'showLoginForm'])->name('empleado.login');
Route::post('/EmpleadoLogin', [EmpleadoLoginController::class, 'login'])->name('Empleadologin');
Route::get('/obtenerFechaInicioConcurso', [EmpleadoLoginController::class, 'obtenerFechaInicioConcurso'])->name('obtenerFechaInicioConcurso');
Route::get('/calcular-y-guardar-ganadores', [EmpleadoLoginController::class, 'calcularYGuardarGanadores'])->name('calcularYGuardarGanadores');
/* Route::get('/copiarDatosAHistoricoVotos', [EmpleadoLoginController::class, 'copiarDatosAHistoricoVotos'])->name('copiarDatosAHistoricoVotos'); */
Route::get('/obtenerGanadoresV', [EmpleadoLoginController::class, 'obtenerGanadoresV'])->name('obtenerGanadoresV');
Route::get('/obtenerGanadores', [EmpleadoLoginController::class, 'obtenerGanadores'])->name('obtenerGanadores');


/*------------------------* Formulario de empleado ------------------------*/
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



/*------------------------* Permisos ------------------------*/
Route::get('/Obtenerpermisos', [EmpleadoLoginController::class, 'Obtenerpermisos'])->name('Obtenerpermisos');

/*-----------------------* Dashboard ------------------------*/
Route::get('/dashboard', function () {return view('admin.dashboard');})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/obtenerEmpleados', [AdminController::class, 'obtenerEmpleados'])->name('obtenerEmpleados');
Route::get('/obtenerRegistrosVotos', [AdminController::class, 'obtenerRegistrosVotos']);
Route::get('/obtenerVotosRondas', [AdminController::class, 'obtenerVotosRondas']);


/*-----------------------* Ajustes ------------------------*/
Route::get('/datos', function () { return view('admin.datos');})->middleware(['auth', 'verified'])->name('datos');
Route::post('/importar-empleados', [AdminController::class, 'importarEmpleados']);
Route::post('/vaciarBaseDatos', [AdminController::class, 'vaciarBaseDatos']);
Route::get('/obtenerEvento', [AdminController::class, 'obtenerEvento']);
Route::get('/verificarEventos', [AdminController::class, 'verificarEventos']);
Route::get('/verificarDatosEnTablas', [AdminController::class, 'verificarDatosEnTablas']);
Route::post('/agregarEvento', [AdminController::class, 'agregarEvento']);
Route::get('/detalleEvento/{id}', [AdminController::class, 'detalleEvento']);
Route::post('/editarEvento', [AdminController::class, 'editarEvento']);
Route::get('/verificarGanadores/{id}', [AdminController::class, 'verificarGanadores']);
Route::delete('/eliminarEvento/{id}', [AdminController::class, 'eliminarEvento']);


/*-----------------------* Usuarios ------------------------*/
Route::get('/usuarios', function () { return view('admin.usuarios');})->middleware(['auth', 'verified'])->name('usuarios');
Route::get('/obtenerUsers', [UserController::class, 'obtenerUsers']);
Route::post('/agregarUsuario', [UserController::class, 'agregarUsuario']);
Route::get('/detalleUsuario/{id}', [UserController::class, 'detalleUsuario']);
Route::post('/editarUsuario', [UserController::class, 'editarUsuario']);
Route::delete('/eliminarUsuario/{id}', [UserController::class, 'eliminarUsuario']);
Route::post('/asignarRoles', [UserController::class, 'asignarRoles']);
Route::get('/obtenerRolesUsuario/{idUser}', [UserController::class, 'obtenerRolesUsuario']);

/*-----------------------* Roles ------------------------*/
Route::get('/roles', function () { return view('admin.roles');})->middleware(['auth', 'verified'])->name('roles');
Route::get('/obtenerRoles', [RoleController::class, 'obtenerRoles']);
Route::post('/agregarRol', [RoleController::class, 'agregarRol']);
Route::get('/detalleRol/{id}', [RoleController::class, 'detalleRol']);
Route::post('/editarRol', [RoleController::class, 'editarRol']);
Route::delete('/eliminarRol/{id}', [RoleController::class, 'eliminarRol']);
Route::post('/asignarpermisos', [RoleController::class, 'asignarpermisos']);
Route::get('/obtenerPermisosRol/{idRol}', [RoleController::class, 'obtenerPermisosRol']);


/*-----------------------* Permisos ------------------------*/
Route::get('/permisos', function () { return view('admin.permisos');})->middleware(['auth', 'verified'])->name('permisos');
Route::get('/obtenerPermisos', [PermissionController::class, 'obtenerPermisos']);
Route::post('/agregarPermisos', [PermissionController::class, 'agregarPermisos']);
Route::get('/detallePermiso/{id}', [PermissionController::class, 'detallePermiso']);
Route::post('/editarPermiso', [PermissionController::class, 'editarPermiso']);
Route::delete('/eliminarPermiso/{id}', [PermissionController::class, 'eliminarPermiso']);





/*---------------------- * Acciones Perfil ------------------------*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
