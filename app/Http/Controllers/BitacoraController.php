<?php

namespace App\Http\Controllers;
use App\Models\Bitacora;

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

}
