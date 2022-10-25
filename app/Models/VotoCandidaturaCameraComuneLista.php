<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotoCandidaturaCameraComuneLista extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'voti_candidature_camera_comuni_liste';
}
