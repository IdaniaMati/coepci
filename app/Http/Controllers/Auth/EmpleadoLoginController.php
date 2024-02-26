<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Ganadores;
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
            // Obtener la fecha de inicio del concurso
            $fechaInicioConcurso = $this->obtenerFechaInicioConcurso()->original['fechaInicio'];

            // Obtener la fecha actual
            $fechaActual = Carbon::now();

            // Verificar si aún no ha comenzado el concurso
            if ($fechaActual < $fechaInicioConcurso) {
                return response()->json(['success' => false, 'error' => 'El concurso aún no ha comenzado']);
            }

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
            $concurso = Concurso::orderBy('fechaIni1ronda', 'desc')->first();

            if ($concurso) {
                $fechaInicio = Carbon::parse($concurso->fechaIni1ronda);
                $fechaFin = Carbon::parse($concurso->fechaFin); // Agregado

                return response()->json(['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]); // Modificado
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
    
    public function obtenerFechaFinConcurso()
    {
        try {
            $concurso = Concurso::orderBy('fechaFin', 'desc')->first();

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

    public function obtenerOpcionesVotacion($ronda)
    {
        try {
            if ($ronda == 1) {
                $opciones = Empleado::all();
            } elseif ($ronda == 2) {
                $opciones = DB::table('empleados')
                    ->join('registros', 'empleados.id', '=', 'registros.id_nom')
                    ->select('empleados.id', 'empleados.nombre', 'empleados.apellido_paterno','empleados.apellido_materno', 'registros.id_grup', DB::raw('COUNT(registros.id_nom) AS votos'))
                    ->where('registros.ronda', 1)
                    ->groupBy('empleados.id', 'registros.id_grup')
                    ->orderBy('registros.id_grup')
                    ->orderByDesc('votos')
                    ->get();
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

                // Ordenar alfabéticamente las opciones dentro de cada grupo
            $resultadosLimitados->each(function ($grupo) {
                $grupo->sortBy(function ($opcion) {
                    return $opcion->nombre . ' ' . $opcion->apellido_paterno . ' ' . $opcion->apellido_materno;
                });
            });

                $opciones = $resultadosLimitados->flatMap(function ($grupo) {
                    return $grupo->all();
                });
            }
            //dd($opciones->toSql());
            return response()->json($opciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las opciones de votación']);
        }
    }

    public function obtenerConcursoId() 
    {
        $ultimoConcurso = Concurso::orderBy('id', 'desc')->first();

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

    public function calcularYGuardarGanadores()
    {
        $fechaFinConcurso = Concurso::value('fechaFin');

        if (now() > $fechaFinConcurso) {
            $ultimoConcurso = Concurso::latest('id')->first();
            $idUltimoConcurso = $ultimoConcurso->id;

            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 1, 2);
            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 2, 2);
            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 3, 3);

            return response()->json(['message' => 'Ganadores calculados y guardados correctamente.']);
        } else {
            return response()->json(['message' => 'La fecha de finalización del concurso aún no ha pasado.']);
        }
    }

    public function calcularYGuardarGanadoresPorGrupo($idConcurso, $idGrupo, $numGanadores)
    {
        $resultadosGrupo = DB::table('registros')
            ->select('id_nom', DB::raw('COUNT(id_nom) as votos'))
            ->where('ronda', 2)
            ->where('id_grup', $idGrupo)
            ->groupBy('id_nom')
            ->orderByDesc(DB::raw('COUNT(id_nom)'))
            ->take($numGanadores)
            ->get();

            foreach ($resultadosGrupo as $resultado) {
                $idNom = $resultado->id_nom;
                $votos = $resultado->votos;
        
                $empleado = Empleado::find($idNom);
        
                if ($empleado) {
                    $nombreCompleto = $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno;
        
                    $existente = Ganadores::where('id_conc', $idConcurso)
                        ->where('id_grup', $idGrupo)
                        ->where('id_emp', $nombreCompleto)
                        ->exists();
        
                    if (!$existente) {
                        Ganadores::create([
                            'id_conc' => $idConcurso,
                            'id_grup' => $idGrupo,
                            'id_emp' => $nombreCompleto,
                            'votos' => $votos,
                        ]);
                    }
                }
            }
    }

    public function obtenerGanadoresV()
    {
        try {
            $ultimoConcurso = Concurso::latest()->first();

            if (!$ultimoConcurso) {
                return response()->json(['ganadores' => [], 'message' => 'Aún no hay votaciones para el concurso.']);
            }

            $ganadores = Ganadores::where('id_conc', $ultimoConcurso->id)
                ->select('ganadores.id_emp', 'ganadores.id_grup')
                ->get();

            $ganadoresAgrupados = $ganadores->groupBy('id_grup');

            return response()->json(['ganadores' => $ganadoresAgrupados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerGanadoresHisto()
    {
        try {
            $ultimoConcurso = Concurso::latest()->first();

            if (!$ultimoConcurso) {
                return response()->json(['ganadores' => []]);
            }

            $ganadores = Ganadores::where('id_conc', $ultimoConcurso->id)
                ->select('ganadores.id_emp', 'ganadores.id_grup')
                ->get();

            $ganadoresAgrupados = $ganadores->groupBy('id_grup');

            return response()->json(['ganadores' => $ganadoresAgrupados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerHistorico()
    {
        try {
            $historico = Ganadores::select('concursos.descripcion as concurso', 'ganadores.id_grup', 'ganadores.id_emp')
                ->join('concursos', 'ganadores.id_conc', '=', 'concursos.id')
                ->get();

            $historicoAgrupado = $historico->groupBy('concurso')->map(function ($concurso) {
                return $concurso->groupBy('id_grup')->map(function ($grupo) {
                    return $grupo->pluck('id_emp');
                });
            });

            return response()->json(['historico' => $historicoAgrupado]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
