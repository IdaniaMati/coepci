<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $table = 'bitacora';
    protected $fillable = [
        'id_user',
        'id_depen',
        'action',
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencias::class, 'id_depen');
    }
}
