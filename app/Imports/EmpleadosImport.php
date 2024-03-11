<?php

namespace App\Imports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmpleadosImport implements ToModel, WithHeadingRow
{
    private $idDepen;

    public function __construct($idDepen)
    {
        $this->idDepen = $idDepen;
        //dd($this->idDepen);
    }

    /* public function beforeImport()
    {
        dd($this->idDepen); // Verifica si el valor es correcto antes de la importación
    } */

    public function model(array $row)
    {
        return new Empleado([
            'nombre' => $row['nombre'],
            'apellido_paterno' => $row['apellido_paterno'],
            'apellido_materno' => $row['apellido_materno'],
            'curp' => $row['curp'],
            'cargo' => $row['cargo'],
            'id_grup' => $row['id_grup'],
            'id_depen' => $this->idDepen,
        ]);
    }
}
