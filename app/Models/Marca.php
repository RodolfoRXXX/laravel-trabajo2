<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    //Relacionar la clase con otra tabla de la db y no la definida por el sistema de plurales
    //protected $table = 'x'; <=(estoy relacionando a este modelo con la tabla de nombre "x")

    //vincular la columna PK de la tabla con la consulta de eloquent que considera como PK llamada "id"
    protected $primaryKey = 'idMarca';

    //Deshabilitar timestamps de la base de datos
    public $timestamps = false;
}
