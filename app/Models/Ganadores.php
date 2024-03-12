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
        'id_grup',
        'id_emp',
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class, 'id');
    }
}
