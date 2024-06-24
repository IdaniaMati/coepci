<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoVotos extends Model
{
    use HasFactory;
    protected $table = 'historico_votos';
    protected $fillable = [
        'nombre',
        'id_grup',
        'id_conc',
        'ronda',
        'novotos',
    ];

}
