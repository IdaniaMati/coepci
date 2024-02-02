<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Registro;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class EmpleadoLoginController extends Controller
{

    //login de usuario
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

    public function Principal()
    {
        return view('auth.principal');
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


    public function VotacionEmpleado()
    {
        return view('auth.votacion');
    }

    public function FinVotacion()
    {
        Auth::guard('empleado')->logout();
        return view('auth.votacionfin');

    }

    public function verificarVotoUsuarioActual($ronda)
    {
        $votanteId = Auth::id();
        $yaVoto = Registro::where('id_vot', $votanteId)
            ->where('ronda', $ronda)
            ->exists();

        return response()->json(['yaVoto' => $yaVoto]);
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

    public function obtenerOpcionesVotacion()
    {
        try {
            $opciones = Empleado::all();

            return response()->json($opciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las opciones de votación']);
        }
    }

    public function loginForm()
    {
        return view( 'auth.login' );
    }


}
