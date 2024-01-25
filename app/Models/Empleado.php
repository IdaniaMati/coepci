<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Empleado extends Authenticatable
{
    protected $guard = 'empleado';
    protected $table = 'empleados';

    public function empleados()
{
    return $this->hasMany(Empleado::class, 'id_grup');
}
}
