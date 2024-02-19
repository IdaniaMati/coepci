<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Empleado extends Authenticatable
{
    use HasFactory;
    protected $guard = 'empleado';
    protected $table = 'empleados';
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'curp',
        'cargo',
        'id_grup',
    ];

    public function empleados()
{
    return $this->hasMany(Empleado::class, 'id_grup');
}
}
