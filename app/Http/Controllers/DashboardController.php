<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Concurso;
use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{

    public function showDashboardForm()
    {
        return view('admin.dashboard');
    }

    // ------> Respaldo de base de datos
    public function respaldo()
    {
        return view('admin.respaldo');
    }

    public function exportAllData()
    {
        // Obtener todas las tablas de la base de datos "coepci"
        $tables = DB::select('SHOW TABLES FROM coepci');
    
        $sql = '';
    
        foreach ($tables as $table) {
            $tableName = reset($table);
    
            // Obtener la estructura de la tabla
            $tableStructure = DB::select("SHOW CREATE TABLE $tableName")[0]->{'Create Table'};
            $tableStructure = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $tableStructure);
            $sql .= "$tableStructure;\n\n";
    
            // Obtener los datos de cada tabla
            $rows = DB::table($tableName)->get();
    
            if ($rows->count() > 0) {
                // Obtener los nombres de las columnas
                $columns = collect($rows[0])->keys()->map(function ($columnName) {
                    return "`$columnName`";
                })->implode(', ');
    
                // Generar SQL INSERT con los nombres de las columnas
                $sql .= "INSERT INTO `$tableName` ($columns) VALUES ";
    
                $values = $rows->map(function ($row) {
                    $row = (array) $row;
                    return '(' . implode(', ', array_map(function ($value) {
                        // Si el valor es numérico, no poner comillas
                        return is_numeric($value) ? $value : "'" . addslashes($value) . "'";
                    }, $row)) . ')';
                })->implode(",\n");
    
                $sql .= "$values;\n\n";
            }
        }
    
        // Descargar el archivo SQL
        $fileName = 'coepci_' . date('Y-m-d_H-i-s') . '.sql';
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Type: application/sql');
        echo $sql;
        exit;
    }
    
    
    

    // ------> Respaldo de base de datos

    public function obtenerEmpleados()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }

            $depen_user = $user->id_depen;
            $empleados = Empleado::where('id_depen', $depen_user)
                ->get();

            if ($empleados->isEmpty()) {
                return response()->json(['message' => 'No hay empleados en el sistema.']);
            }

            return response()->json(['empleados' => $empleados]);
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

}
