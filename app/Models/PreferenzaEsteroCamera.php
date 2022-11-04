<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferenzaEsteroCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'preferenze_estero_camera';
}
