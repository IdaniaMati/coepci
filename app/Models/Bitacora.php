<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $table = 'bitacora';

    public function dependencia()
    {
        return $this->belongsTo(Dependencias::class, 'id_depen');
    }
}
