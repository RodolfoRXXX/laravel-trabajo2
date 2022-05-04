<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    //vincular el idCategoria con id de blade
    protected $primaryKey = 'idCategoria';

    //anular timestamps
    public $timestamps = false;
}
