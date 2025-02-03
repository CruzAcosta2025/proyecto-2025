<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CentroMedico extends Model
{
    use HasFactory;

    protected $table = 'centro_medico';

    protected $fillable = ['nombre', 'direccion', 'ruc', 'color_tema', 'estado'];
}

