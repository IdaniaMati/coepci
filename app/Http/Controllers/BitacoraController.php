<?php

namespace App\Http\Controllers;
use App\Models\Bitacora;
use App\Models\User;
use App\Models\Dependencias;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{

    public function showBitacoraForm()
    {
        return view('admin.bitacora');
    }

    public function obtenerBitacora()
    {
        $bitacoras = Bitacora::latest()->get();
        return response()->json($bitacoras);
    }

    public function obtenerUsuarioBitacora($id_user)
    {
        $usuario = User::find($id_user);
        return response()->json($usuario);
    }

    public function obtenerDependenciaBitacora($id_depen)
    {
        $dependencia = Dependencias::find($id_depen);
        return response()->json($dependencia);
    }

}
