<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisultatoCandidaturaCollegioUninominaleCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_candidature_collegi_uninominale_camera';

    public function comune()
    {
        return $this->belongsTo(Comune::class, 'comune_id');
    }
}