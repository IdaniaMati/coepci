<?php

namespace App\Http\Controllers;
use App\Models\Registro;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function nominaciones()
    {
        return view('public.nominacion');
    }

    public function resultado()
    {
        return view('public.ronda');
    }

    public function historico()
    {
        return view('public.historico');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
