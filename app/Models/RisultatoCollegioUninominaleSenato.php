<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisultatoCollegioUninominaleSenato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_collegi_uninominale_senato';

    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'candidato_id');
    }
}
