<?php

namespace App\Helpers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MyHelper {
    /**
     * Registra una acción en la bitácora del sistema.
     *
     * @param string $accion Descripción de la acción a registrar.
     * @return void
     */
    public static function registrarAccion($accion)
    {
        //$userID = auth()->id();
        $userID = Auth::user()->name;
        $dependenciaID = Auth::user()->id_depen;

        DB::beginTransaction();
        try {
            $log = new Bitacora();
            $log->id_user = $userID;
            $log->id_depen = $dependenciaID;
            $log->action = $accion;
            $log->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al registrar la acción en la bitácora: ' . $e->getMessage());
        }
    }
}
