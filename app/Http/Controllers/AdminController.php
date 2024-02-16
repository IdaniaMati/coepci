<?php

namespace App\Http\Controllers;

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

class AdminController extends Controller
{
    /* ============dashnoard============ */
    public function obtenerEmpleados()
    {
        try {
            $empleados = Empleado::all();

            if ($empleados->isEmpty()) {
                return response()->json(['message' => 'No hay empleados en el sistema.']);
            }

            return response()->json(['empleados' => $empleados]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

/*     public function obtenerRegistrosVotos()
    {
        try {
            $registros = Registro::all();
            return response()->json(['registros' => $registros], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener registros de votos'], 500);
        }
    } */

    public function obtenerRegistrosVotos()
    {
        try {
            $votosRonda1 = Registro::where('ronda', 1)->distinct('id_vot')->count('id_vot');
            $votosRonda2 = Registro::where('ronda', 2)->distinct('id_vot')->count('id_vot');
            $empleadosTotales = Empleado::count(); // Supongamos que tienes un modelo Empleado

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







}
