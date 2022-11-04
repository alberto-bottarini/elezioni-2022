<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotoListaEsteroCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'voti_liste_estero_camera';
}
