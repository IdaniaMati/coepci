<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganadores extends Model
{
    use HasFactory;
    protected $table = 'ganadores';
    protected $fillable = [
        'id_conc',
        'id_emp',
        'id_grup',
        'curp',
        'id_cargo',
        'documento',
        'estado'
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class, 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_emp', 'id');
    }
}
