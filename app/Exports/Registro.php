<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class Registro implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Registro::all();
    }
}
