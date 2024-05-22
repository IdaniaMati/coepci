<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;
    protected $table = 'concursos';
    protected $fillable = [
        'descripcion',
        'fechaIni1ronda',
        'fechaIni1ronda',
        'fechaFin',
        'comentario',
        'id_depen',
    ];

    public function ganadores()
    {
        return $this->hasMany(Ganadores::class, 'id_conc');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencias::class, 'id_depen');
    }
}
