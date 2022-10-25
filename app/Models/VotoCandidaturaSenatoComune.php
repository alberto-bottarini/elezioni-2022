<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotoCandidaturaSenatoComune extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'voti_candidature_senato_comuni';

    public function comune()
    {
        return $this->belongsTo(Comune::class, 'comune_id');
    }
}
