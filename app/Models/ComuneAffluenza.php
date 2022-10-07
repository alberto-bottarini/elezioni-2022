<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComuneAffluenza extends Model
{
    use HasFactory;

    public $table = 'comuni_affluenze';
    public $timestamps = false;
}
