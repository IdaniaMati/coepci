<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Ganadores;
use App\Models\Registro;
use App\Models\HistoricoVotos;
use App\Models\Dependencias;
use App\Models\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class EmpleadoLoginController extends Controller
{

    public function Obtenerpermisos()
    {
        $idUser = Auth::user()->id;
        $list_users = User::select('permissions.name')
        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->where('model_has_roles.model_id', $idUser)
        ->get();
        return $list_users;

    }

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
            $fechaInicioConcursoResponse = $this->obtenerFechaInicioConcurso($request);

            if (!$fechaInicioConcursoResponse->original['success']) {
                return response()->json(['success' => false, 'error' => $fechaInicioConcursoResponse->original['error']]);
            }

            $fechaInicioConcurso = $fechaInicioConcursoResponse->original['fechaInicio'];
            $fechaActual = Carbon::now();

            if ($fechaActual < $fechaInicioConcurso) {
                return response()->json(['success' => false, 'error' => 'El concurso aún no ha comenzado']);
            }

            $idDependenciaUsuario = $user->id_depen;

            $ultimoEvento = Concurso::where('id_depen', $idDependenciaUsuario)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$ultimoEvento) {
                return response()->json(['success' => false, 'error' => 'No hay eventos para la dependencia del usuario']);
            }

            $fechaFinEvento = $ultimoEvento->fechaFin;

            if ($fechaActual > $fechaFinEvento) {
                return response()->json(['success' => false, 'error' => 'El concurso ya ha finalizado']);
            }

            Auth::guard('empleado')->login($user);

            return response()->json(['success' => true, 'redirect' => route('Principal')]);
        } else {
            return response()->json(['success' => false, 'error' => 'CURP no encontrada']);
        }
    }

    public function obtenerFechaInicioConcurso(Request $request)
    {
        try {
            $curp = $request->input('curp');

            $usuario = Empleado::where('curp', $curp)->first();

            if (!$usuario) {
                return response()->json(['success' => false, 'error' => 'Usuario no encontrado'], 404);
            }

            $idDependenciaUsuario = $usuario->id_depen;

            $concurso = Concurso::where('id_depen', $idDependenciaUsuario)
                ->orderBy('fechaIni1ronda', 'desc')
                ->first();

            if (!$concurso) {
                return response()->json(['success' => false, 'error' => 'No se encontró información del concurso para la dependencia del usuario'], 404);
            }

            $fechaInicio = Carbon::parse($concurso->fechaIni1ronda);
            $fechaFin = Carbon::parse($concurso->fechaFin);

            return response()->json(['success' => true, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
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


    // public function obtenerSegundaFechaConcurso()
    // {
    //     try {

    //         $user = Auth::user();

    //         if (!$user) {
    //             return response()->json(['message' => 'Usuario no autenticado'], 401);
    //         }

    //         $idDependenciaUsuario = $user->id_depen;

    //         $concurso = Concurso::where('id_depen', $idDependenciaUsuario)
    //             ->orderBy('fechaIni1ronda', 'desc')
    //             ->first();

    //         if ($concurso) {
    //             $fechaSegunda = Carbon::parse($concurso->fechaIni2ronda);

    //             return response()->json(['fechaSegundo' => $fechaSegunda]);
    //         } else {
    //             return response()->json(['error' => 'No se encontró información del concurso para la dependencia del usuario'], 404);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function obtenerSegundaFechaConcurso(){
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $idDependenciaUsuario = $user->id_depen;

            $concurso = Concurso::where('id_depen', $idDependenciaUsuario)
                ->orderBy('fechaIni1ronda', 'desc')
                ->first();

            if ($concurso) {
                $fechaSegunda = Carbon::parse($concurso->fechaIni2ronda);
                $fechaActual = now();

                // return $fechaSegunda >= $fechaActual ?
                //     response()->json(['fechaSegundo' => $fechaSegunda, 'bloquearPrimeraRonda' => true]) :
                //     response()->json(['fechaSegundo' => $fechaSegunda, 'bloquearPrimeraRonda' => false]);
                return response()->json([
                    'fechaSegundo' => $fechaSegunda->toDateTimeString(),
                    'bloquearPrimeraRonda' => $fechaSegunda >= $fechaActual
                ]);
            } else {
                return response()->json(['error' => 'No se encontró información del concurso para la dependencia del usuario'], 404);
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

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $idDependenciaUsuario = $user->id_depen;

            if ($ronda == 1) {
                $opciones = Empleado::where('id_depen', $idDependenciaUsuario)
                ->get();
            } elseif ($ronda == 2) {
                $opciones = DB::table('empleados')
                    ->join('registros', 'empleados.id', '=', 'registros.id_nom')
                    ->select('empleados.id', 'empleados.nombre', 'empleados.apellido_paterno','empleados.apellido_materno', 'registros.id_grup', DB::raw('COUNT(registros.id_nom) AS votos'))
                    ->where('registros.ronda', 1)
                    ->where('empleados.id_depen', $idDependenciaUsuario) //empleados de la dependencia
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

        $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

        $idDependenciaUsuario = $user->id_depen;

        $ultimoConcurso = Concurso::where('id_depen', $idDependenciaUsuario)
            ->orderBy('created_at', 'desc')
            ->first();

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

    public function historicoDependencia()
    {
        return view('public.historico_dependencia');
    }



        public function resultado(Request $request)
    {
        /* $ronda = $request->get('ronda', 1);

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

        return view('public.ronda', ['resultados' => $resultados, 'ronda' => $ronda]); */
        return view( 'public.ronda' );
    }

    public function resultadoPorDependencia(Request $request)
    {
        $idDependencia = $request->query('idDependencia');
        return view( 'public.ronda_dependencia', compact('idDependencia') );
    }


    /* ============Vistas Públicas============ */
    public function obtenerResultados(Request $request)
    {
        $ronda = $request->get('ronda', 1);

        $idDependencia = $request->get('idDependencia');

        $registros = Registro::where('ronda', $ronda)
            ->whereHas('concurso', function ($query) use ($idDependencia) {
                $query->where('id_depen', $idDependencia);
            })
            ->get();

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

    public function obtenerGanadoresV(Request $request)
    {
        try {
            $idDependenciaSeleccionada = $request->get('idDependencia');

            $ultimoConcurso = Concurso::where('id_depen', $idDependenciaSeleccionada)->latest()->first();

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

    /* public function obtenerGanadoresV()
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
    } */

    /* public function obtenerResultados(Request $request)
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
 */
    /* public function calcularYGuardarGanadores() //Resultados automaticos
    {

        $ultimoConcurso = Concurso::latest('id')->first();
        $fechaFinConcurso = $ultimoConcurso->fechaFin;

        if (now() > $fechaFinConcurso) {

            $idUltimoConcurso = $ultimoConcurso->id;

            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 1, 2);
            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 2, 2);
            $this->calcularYGuardarGanadoresPorGrupo($idUltimoConcurso, 3, 3);

            $this->copiarDatosAHistoricoVotos();

            return response()->json(['message' => 'Ganadores calculados y guardados correctamente.']);
        } else {
            return response()->json(['message' => 'La fecha de finalización del concurso aún no ha pasado.']);
        }
    } */

    public function calcularYGuardarGanadores()
    {
        $dependenciasConConcursos = Concurso::distinct('id_depen')->pluck('id_depen');

        $ganadoresCalculados = false;

        foreach ($dependenciasConConcursos as $idDependencia) {
            $ultimoConcurso = Concurso::where('id_depen', $idDependencia)->latest('id')->first();

            if ($ultimoConcurso) {
                $fechaFinConcurso = $ultimoConcurso->fechaFin;


                if (now() > $fechaFinConcurso) {
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 1, 2);
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 2, 2);
                    $this->calcularYGuardarGanadoresPorGrupo($ultimoConcurso->id, 3, 3);

                    $this->copiarDatosAHistoricoVotos($idDependencia);

                    $ganadoresCalculados = true;
                }
            }
        }

        if ($ganadoresCalculados) {
            return response()->json(['message' => 'Ganadores calculados y guardados correctamente para todas las dependencias.']);
        } else {
            return response()->json(['message' => 'No hay concursos activos en ninguna dependencia.']);
        }
    }

    public function calcularYGuardarGanadoresPorGrupo($idConcurso, $idGrupo, $numGanadores)
    {
        $resultadosGrupo = DB::table('registros')
            ->select('id_nom', DB::raw('COUNT(id_nom) as votos'))
            ->where('ronda', 2)
            ->where('id_grup', $idGrupo)
            ->where('id_conc', $idConcurso)
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

    public function copiarDatosAHistoricoVotos()
    {
        try {
            $datosRegistros = DB::table('registros')
                ->select(/* 'id_vot', */'id_nom', 'id_grup', 'id_conc', 'ronda')
                ->get();

            foreach ($datosRegistros as $registro) {
                /* $idVot = $registro->id_vot; */
                $idNom = $registro->id_nom;
                $grupo = $registro->id_grup;
                $concurso = $registro->id_conc;
                $ronda = $registro->ronda;

                $empleado = Empleado::find($idNom);

                if ($empleado) {
                    $nombreCompleto = $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno;

                    $numVotos = DB::table('registros')
                    ->where('id_nom', $idNom)
                    ->where('ronda', $ronda)
                    ->count();

                    $existente = HistoricoVotos::/* where('id_vot', $idVot) */
                        where('nombre', $nombreCompleto)
                        ->where('id_grup', $grupo)
                        ->where('id_conc', $concurso)
                        ->where('ronda', $ronda)
                        ->where('novotos', $numVotos)
                        ->exists();

                    if (!$existente) {

                        HistoricoVotos::create([
                            /* 'id_vot' => $idVot, */
                            'nombre' => $nombreCompleto,
                            'id_grup' => $grupo,
                            'id_conc' => $concurso,
                            'ronda' => $ronda,
                            'novotos' => $numVotos,
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => 'Datos copiados a historico_votos correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

    public function obtenerHistorico(Request $request)
    {
        try {

            $idDependencia = $request->input('idDependencia');

            $historico = Ganadores::select('concursos.id as id_conc','concursos.descripcion as concurso', 'concursos.fechaIni1ronda', 'ganadores.id_grup', 'ganadores.id_emp')
                ->join('concursos', 'ganadores.id_conc', '=', 'concursos.id')
                ->where('concursos.id_depen', $idDependencia)
                ->get();

            $historicoAgrupado = $historico->groupBy(function ($item) {
                return Carbon::parse($item->fechaIni1ronda)->format('Y');
            })->map(function ($concursoPorAno) {
                return $concursoPorAno->groupBy('concurso')->map(function ($grupoPorConcurso) {
                    return [
                        'id_conc' => $grupoPorConcurso->first()->id_conc,
                        'descripcion' => $grupoPorConcurso->first()->concurso,
                        'grupos' => $grupoPorConcurso->groupBy('id_grup')->map(function ($ganadoresPorGrupo) {
                            return $ganadoresPorGrupo->pluck('id_emp');
                        }),
                    ];
                });
            });


            return response()->json(['historico' => $historicoAgrupado]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function obtenerVotosTodosEmpleados($idConcurso)
    {
        try {

            $idDepen = Concurso::where('id', $idConcurso)->value('id_depen');

            $votosPorRondaYGrupo = HistoricoVotos::select('nombre', 'id_grup', 'id_conc', 'ronda', 'novotos')
            ->where('id_conc', $idConcurso)
            ->orderBy('ronda')
            ->orderBy('id_grup')
            ->orderByDesc('novotos')
            ->get();


                $votosAgrupados = $votosPorRondaYGrupo->groupBy(['ronda', 'id_grup']);

            return response()->json(['votosPorRondaYGrupo' => $votosAgrupados, 'idDepen' => $idDepen]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /* public function obtenerHistorico()
    {
        try {
            $historico = Ganadores::select('concursos.id as id_conc','concursos.descripcion as concurso', 'concursos.fechaIni1ronda', 'ganadores.id_grup', 'ganadores.id_emp')
                ->join('concursos', 'ganadores.id_conc', '=', 'concursos.id')
                ->get();

            $historicoAgrupado = $historico->groupBy(function ($item) {
                return Carbon::parse($item->fechaIni1ronda)->format('Y');
            })->map(function ($concursoPorAno) {
                return $concursoPorAno->groupBy('concurso')->map(function ($grupoPorConcurso) {
                    return [
                        'id_conc' => $grupoPorConcurso->first()->id_conc,
                        'descripcion' => $grupoPorConcurso->first()->concurso,
                        'grupos' => $grupoPorConcurso->groupBy('id_grup')->map(function ($ganadoresPorGrupo) {
                            return $ganadoresPorGrupo->pluck('id_emp');
                        }),
                    ];
                });
            });


            return response()->json(['historico' => $historicoAgrupado]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerVotosTodosEmpleados($idConcurso)
    {
        try {
            $votosPorRondaYGrupo = HistoricoVotos::select('nombre', 'id_grup', 'id_conc', 'ronda', 'novotos')
                ->where('id_conc', $idConcurso)
                ->orderBy('ronda')
                ->orderBy('id_grup')
                ->orderByDesc('novotos')
                ->get();


                $votosAgrupados = $votosPorRondaYGrupo->groupBy(['ronda', 'id_grup']);

            return response()->json(['votosPorRondaYGrupo' => $votosAgrupados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } */



}
