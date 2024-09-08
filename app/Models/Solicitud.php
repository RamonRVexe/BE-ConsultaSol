<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'Resultados2024';

    protected $fillable = [
        'idcovocatoria',
        'id',
        'folio',
        'estatussolicitud',
        'curp',
        'nombre',
        'escuela',
        'campus',
        'nivelacademico',
        'carrera',
        'ganador',
        'motivorechazo',
        'comentario',
        'comentario2'
    ];

}
