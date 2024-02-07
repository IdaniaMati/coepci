<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Registro;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class EmpleadoLoginController extends Controller
{

    /* ============login de usuario============ */
    public function showLoginForm()
    {
        return view('auth.empleado-login');
    }

    public function login(Request $request)
    {
        $curp = $request->input('curp');

        $user = Empleado::where('curp', $curp)->first();

        if ($user) {
            Auth::guard('empleado')->login($user);

            return response()->json(['success' => true, 'redirect' => route('Principal')]); /* VotacionEmpleado */
        } else {
            return response()->json(['success' => false]);
        }
    }


    /* ============Votación Usuario============ */
    public function Principal()
    {
        return view('auth.principal');
    }

    public function verificarVotoUsuarioActual($ronda)
    {
        $votanteId = Auth::id();
        $yaVoto = Registro::where('id_vot', $votanteId)
            ->where('ronda', $ronda)
            ->exists();

        return response()->json(['yaVoto' => $yaVoto]);
    }

    public function obtenerIdUsuarioAutenticado(Request $request)
    {
        try {
            $idUsuario = Auth::id();

            return response()->json(['id' => $idUsuario]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerFechaInicioConcurso()
    {
        try {
            $concurso = Concurso::first();

            if ($concurso) {
                $fechaInicio = Carbon::parse($concurso->fechaIni1ronda);

                return response()->json(['fechaInicio' => $fechaInicio]);
            } else {
                return response()->json(['error' => 'No se encontró información del concurso'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerSegundaFechaConcurso()
    {
        try {
            $concurso = Concurso::first();

            if ($concurso) {
                $fechaSegunda = Carbon::parse($concurso->fechaIni2ronda);

                return response()->json(['fechaSegundo' => $fechaSegunda]);
            } else {
                return response()->json(['error' => 'No se encontró información del concurso'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerOpcionesVotacion($ronda)
    {
        try {
            if ($ronda == 1) {
                // Lógica para la primera ronda (sin cambios)
                $opciones = Empleado::all();
            } else {
                // Lógica para la segunda ronda
                $opciones = DB::table('empleados')
                    ->join('registros', 'empleados.id', '=', 'registros.id_nom')
                    ->select('empleados.id', 'empleados.nombre', 'registros.id_grup', DB::raw('COUNT(registros.id_nom) AS votos'))
                    ->where('registros.ronda', 2)
                    ->groupBy('empleados.id', 'empleados.nombre', 'registros.id_grup')
                    ->orderBy('registros.id_grup')
                    ->orderByDesc('votos')
                    ->get();
    
                // Filtrar solo los primeros 5 de cada grupo
                $resultadosLimitados = collect();
    
                $opciones->each(function ($opcion) use (&$resultadosLimitados) {
                    $grupo = $opcion->id_grup;
    
                    if (!$resultadosLimitados->has($grupo)) {
                        $resultadosLimitados->put($grupo, collect());
                    }
    
                    if ($resultadosLimitados[$grupo]->count() < 5) {
                        $resultadosLimitados[$grupo]->push($opcion);
                    }
                });
    
                $opciones = $resultadosLimitados->flatMap(function ($grupo) {
                    return $grupo->all();
                });
            }
    
            return response()->json($opciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las opciones de votación']);
        }
    }


    public function obtenerConcursoId()
    {
        $ultimoConcurso = Concurso::latest()->first();

        return response()->json(['ultimoConcursoId' => $ultimoConcurso->id]);
    }

    public function VotacionEmpleado()
    {
        return view('auth.votacion');
    }

    public function enviarVotacion(Request $request)
    {
        try {
            $request->validate([
                'votante_id' => 'required',
                'candidato_id' => 'required',
                'grupo_id' => 'required',
                'concurso_id' => 'required',
                'ronda' => 'required',
            ]);

            Registro::create([
                'id_vot' => $request->input('votante_id'),
                'id_nom' => $request->input('candidato_id'),
                'id_grup' => $request->input('grupo_id'),
                'id_conc' => $request->input('concurso_id'),
                'ronda' => $request->input('ronda'),
            ]);

            return response()->json(['success' => true, 'message' => 'Voto registrado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al registrar el voto', 'error' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::guard('empleado')->logout();
        return redirect()->route('empleado.login');
    }


    /* ============Vistas Públicas============ */
    public function obtenerResultados(Request $request)
    {
        $ronda = $request->get('ronda', 1);

        $registros = Registro::where('ronda', $ronda)->get();

        if ($registros->isEmpty()) {
            return response()->json(['message' => "No hay resultados para la Ronda $ronda."], 404);
        }

        $resultados = [];

        foreach ($registros as $registro) {
            $empleado = Empleado::find($registro->id_nom);

            $grupo = $registro->id_grup;

            if (!isset($resultados[$grupo])) {
                $resultados[$grupo] = [];
            }

            if (isset($resultados[$grupo][$empleado->id])) {
                $resultados[$grupo][$empleado->id]['votos']++;
            } else {
                $resultados[$grupo][$empleado->id] = [
                    'nombre' => $empleado->nombre,
                    'apellido_paterno' => $empleado->apellido_paterno,
                    'apellido_materno' => $empleado->apellido_materno,
                    'votos' => 1,
                ];
            }
        }

        foreach ($resultados as &$grupoResultados) {
            usort($grupoResultados, function ($a, $b) {
                return $b['votos'] - $a['votos'];
            });
        }

        return response()->json($resultados);
    }

    /* ============login de usuario============ */
    public function loginForm()
    {
        return view( 'auth.login' );
    }

    public function nominaciones()
    {
        Auth::guard('empleado')->logout();
        return view('public.nominacion');
    }

    public function ResultadosVotacion()
    {
        return view('public.ronda');
    }

    public function historico()
    {
        return view('public.historico');
    }

    public function resultado(Request $request)
    {
        $ronda = $request->get('ronda', 1);

        $registros = Registro::where('ronda', $ronda)->get();

        if ($registros->isEmpty()) {
            return view('public.ronda', ['resultados' => null, 'ronda' => $ronda]);
        }

        $resultados = [];

        foreach ($registros as $registro) {
            $empleado = Empleado::find($registro->id_nom);

            $grupo = $registro->id_grup;

            if (!isset($resultados[$grupo])) {
                $resultados[$grupo] = [];
            }

            if (isset($resultados[$grupo][$empleado->id])) {
                $resultados[$grupo][$empleado->id]['votos']++;
            } else {
                $resultados[$grupo][$empleado->id] = [
                    'nombre' => $empleado->nombre,
                    'apellido_paterno' => $empleado->apellido_paterno,
                    'apellido_materno' => $empleado->apellido_materno,
                    'votos' => 1,
                ];
            }
        }

        foreach ($resultados as &$grupoResultados) {
            usort($grupoResultados, function ($a, $b) {
                return $b['votos'] - $a['votos'];
            });
        }

        return view('public.ronda', ['resultados' => $resultados, 'ronda' => $ronda]);
    }

}