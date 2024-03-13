<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VedaController extends Controller
{
    public function showVedaForm()
    {
        return view('admin.veda');
    }
}
