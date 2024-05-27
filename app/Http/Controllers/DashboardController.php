<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Registro;
use App\Models\Grupo;
use Illuminate\Validation\Rule;
use App\Helpers\MyHelper;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function showDashboardForm()
    {
        return view('admin.dashboard');

    }

    // public function obtenerEmpleados()
    // {
    //     try {

    //         $user = Auth::user();

    //         if (!$user) {
    //             return response()->json(['message' => 'Usuario no autenticado'], 401);
    //         }

    //         $depen_user = $user->id_depen;

    //         if ($depen_user == 1) {
    //             $empleados = Empleado::all();
    //         } else{
    //             $empleados = Empleado::where('id_depen', $depen_user)
    //             ->get();
    //         }

    //         if ($empleados->isEmpty()) {
    //             return response()->json(['message' => 'No hay empleados en el sistema.']);
    //         }

    //         return response()->json(['empleados' => $empleados]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function obtenerEmpleados(Request $request)
    {
        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $request->input('dependencia', $user->id_depen);

            if ($depen_user == 1) {
                $empleados = Empleado::all();
            } else {
                $empleados = Empleado::where('id_depen', $depen_user)->get();
            }

            if ($empleados->isEmpty()) {
                return response()->json(['message' => 'No hay empleados en el sistema.']);
            }

            // Retornar los empleados en formato JSON
            return response()->json(['empleados' => $empleados]);
        } catch (\Exception $e) {
            // Si ocurre una excepción, retornar un mensaje de error con el código 500 (error interno del servidor)
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerGrupos()
    {
        $grupos = Grupo::all();
        return response()->json(['success' => true, 'grupos' => $grupos]);
    }


    public function agregarEmpleado(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $request->validate([
                'id_grup' => 'required',
                'curp' => [
                    'required',
                    'max:18',
                    Rule::unique('empleados')->where(function ($query) use ($request) {
                        return $query->where('curp', strtoupper($request->curp));
                    }),
                ],
                'nombre' => 'required',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'cargo' => 'required',
            ]);

            $nuevoEmpleado = new Empleado;
            $nuevoEmpleado->id_grup = $request->id_grup;
            $nuevoEmpleado->curp = strtoupper ($request->curp);
            $nuevoEmpleado->nombre = $request->nombre;
            $nuevoEmpleado->apellido_paterno = $request->apellido_paterno;
            $nuevoEmpleado->apellido_materno = $request->apellido_materno;
            $nuevoEmpleado->cargo = $request->cargo;

            $nuevoEmpleado->id_depen = $user->id_depen;

            $nuevoEmpleado->save();
            MyHelper::registrarAccion('Se agrego al empleado: ' . $nuevoEmpleado ->nombre);

            return response()->json(['success' => true, 'message' => ' Empleado guardado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detalleEmpleado($id)
    {

        $empleado = Empleado::where('id',$id)->get();
        return response()->json($empleado);

    }

    public function editarEmpleado(Request $request)
    {

        try {
            $request->validate([
                'id' => 'required|exists:empleados,id',
                'id_grup' => 'required|exists:grupos,id',

            ]);

            $empleadoExistente = DB::table("empleados")
                ->where("curp", $request['curp'])
                ->where("id", '!=', $request['id'])
                ->first();

            if ($empleadoExistente) {
                return response()->json(['success' => false, 'message' => 'Ya existe una empleado con esa CURP.']);
            }

            $empleado = Empleado::findOrFail($request->input('id'));


            $empleado->nombre = $request->input('nombre');
            $empleado->apellido_paterno = $request->input('apellido_paterno');
            $empleado->apellido_materno = $request->input('apellido_materno');
            $empleado->curp = $request->input('curp');
            $empleado->cargo = $request->input('cargo');
            $empleado->id_grup = $request->input('id_grup');

            $empleado->save();

            MyHelper::registrarAccion('Se editó al empleado: ' . $empleado -> curp);

            return response()->json(['success' => true, 'message' => 'Empleado actualizado exitosamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function eliminarEmpleado($id)
    {
        try {
            // Empleado::findOrFail($id)->delete();
            // MyHelper::registrarAccion('Se elimino al empleado con CURP: ' . $id -> curp);
            $empleado = Empleado::findOrFail($id);
            $nombre = $empleado->nombre;
            $empleado->delete();

            MyHelper::registrarAccion('Se eliminó al empleado: ' . $nombre);

            return response()->json(['success' => true, 'message' => 'Empleado eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerRegistrosVotos()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $concursosDependencia = Concurso::where('id_depen', $user->id_depen)->pluck('id');

            $votosRonda1 = 0;
            $votosRonda2 = 0;
                if ($concursosDependencia->isNotEmpty()) {
                    $votosRonda1 = Registro::where('ronda', 1)->whereIn('id_conc', $concursosDependencia)->distinct('id_vot')->count('id_vot');
                    $votosRonda2 = Registro::where('ronda', 2)->whereIn('id_conc', $concursosDependencia)->distinct('id_vot')->count('id_vot');
                }

            $depen_user = $user->id_depen;

            /* $votosRonda1 = Registro::where('ronda', 1)->where('id_depen', $depen_user)->distinct('id_vot')->count('id_vot');
            $votosRonda2 = Registro::where('ronda', 2)->where('id_depen', $depen_user)->distinct('id_vot')->count('id_vot'); */
            $empleadosTotales = Empleado::where('id_depen', $depen_user)->count();

            $empleadosNoVotaronRonda1 = $empleadosTotales - $votosRonda1;
            $empleadosNoVotaronRonda2 = $empleadosTotales - $votosRonda2;

            return response()->json([
                'votosRonda1' => $votosRonda1,
                'votosRonda2' => $votosRonda2,
                'empleadosNoVotaronRonda1' => $empleadosNoVotaronRonda1,
                'empleadosNoVotaronRonda2' => $empleadosNoVotaronRonda2,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener registros de votos por ronda'], 500);
        }
    }

    public function obtenerVotosRondas()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;

            $empleados = Empleado::where('id_depen', $depen_user)->get();
            $resultados = [];

            foreach ($empleados as $empleado) {
                $votoRonda1 = Registro::where('id_vot', $empleado->id)->where('ronda', 1)->exists();
                $votoRonda2 = Registro::where('id_vot', $empleado->id)->where('ronda', 2)->exists();

                $resultados[] = [
                    'id_empleado' => $empleado->id,
                    'voto_ronda1' => $votoRonda1,
                    'voto_ronda2' => $votoRonda2,
                ];
            }

            return response()->json(['resultados' => $resultados], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los resultados de votos en las rondas.'], 500);
        }
    }

    public function obtenerDependenciaDashboard($id_depen)
    {
        $dependencia = Empleado::find($id_depen);
        return response()->json($dependencia);
    }

    // public function showDashboardDependencia()
    // {

    //     $user = Auth::user();


    //     $showDependenciaSelect = ($user->id_depen === 1);


    //     $dependencias = $showDependenciaSelect ? Empleado::all() : [];

    //     return view('admin.dashboard', [
    //         'showDependenciaSelect' => $showDependenciaSelect,
    //         'dependencias' => $dependencias
    //     ]);
    // }


}
