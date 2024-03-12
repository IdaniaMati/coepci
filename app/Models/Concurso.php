<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;
    protected $table = 'concursos';

    public function ganadores()
    {
        return $this->hasMany(Ganadores::class, 'id_conc');
    }
}
