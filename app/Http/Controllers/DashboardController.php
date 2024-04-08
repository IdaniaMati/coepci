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
use Illuminate\Support\Facades\File;


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
        $tables = DB::select('SHOW TABLES FROM coepci');
    
        $sql = "
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
";
        
        $totalSize = 0;

        foreach ($tables as $table) {
            $tableName = reset($table);

            $tableStructure = DB::select("SHOW CREATE TABLE $tableName")[0]->{'Create Table'};
            $tableStructure = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $tableStructure);
            $sql .= "$tableStructure;\n\n";

            $rows = DB::table($tableName)->get();
    
            if ($rows->count() > 0) {
                $columns = collect($rows[0])->keys()->map(function ($columnName) {
                    return "`$columnName`";
                })->implode(', ');
    
                $sql .= "INSERT INTO `$tableName` ($columns) VALUES ";
    
                $values = $rows->map(function ($row) {
                    $row = (array) $row;
                    return '(' . implode(', ', array_map(function ($value) {
                        return is_numeric($value) ? $value : "'" . addslashes($value) . "'";
                    }, $row)) . ')';
                })->implode(",\n");
    
            $sql .= "$values;\n\n";
            $totalSize += strlen($values);
            }
        }

        $sql.="
/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
";

        $this->cleanupOldBackups();

        $fileName = 'coepci_' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = Storage::disk('backups')->path($fileName);
        File::put($filePath, $sql);

    }

    private function cleanupOldBackups()
    {
        $backupDirectory = Storage::disk('backups')->path('/');
        $files = Storage::disk('backups')->files();

        if (count($files) >= 10) {
            usort($files, function($a, $b) use ($backupDirectory) {
                $fileA = $backupDirectory . $a;
                $fileB = $backupDirectory . $b;
                return filectime($fileA) - filectime($fileB);
            });

            $oldestFile = $backupDirectory . $files[0];
            Storage::disk('backups')->delete($files[0]);
            
            DB::table('respaldo')->where('filename', $files[0])->delete();
        }
    }

    public function getBackupFileInfo()
    {
        $backupDirectory = Storage::disk('backups')->path('/');
        $files = Storage::disk('backups')->files();
        $fileInfo = [];

        $existingFilesCount = DB::table('respaldo')->count();
        if ($existingFilesCount >= 10) {
            return response()->json(['message' => 'Cannot add more backups. Limit reached.'], 403);
        }
    
        foreach ($files as $file) {

            if (count($fileInfo) >= 10) {
                break;
            }

            $filePath = $backupDirectory . $file;
            $creationDate = date('Y-m-d H:i:s', filectime($filePath));
            $fileSizeInBytes = filesize($filePath);
            $fileSizeInMB = round($fileSizeInBytes / 1024 / 1024, 2);
    
            $existingFile = DB::table('respaldo')->where('filename', $file)->first();
            if (!$existingFile) {
                DB::table('respaldo')->insert([
                    'filename' => $file,
                    'creation_date' => $creationDate,
                    'size_mb' => $fileSizeInMB
                ]);

                
            }

            $fileInfo[] = [
                'filename' => $file,
                'creation_date' => $creationDate,
                'size_mb' => $fileSizeInMB
            ];
        }
    
        return response()->json(['message' => 'Backup info retrieved successfully']);
    }
    
    public function getBackupList()
    {
        $backups = DB::table('respaldo')->get();
        
        return response()->json(['backups' => $backups]);
    }
    
    public function downloadBackup($filename)
    {
        $filePath = Storage::disk('backups')->path($filename);
        
        if (Storage::disk('backups')->exists($filename)) {
            return response()->download($filePath, $filename, ['Content-Type' => 'application/sql']);
        } else {
            // Si el archivo no existe, devolver una respuesta de error
            return response()->json(['error' => 'El archivo de respaldo no existe'], 404);
        }
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
