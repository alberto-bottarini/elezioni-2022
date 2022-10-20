<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisultatoCandidaturaCollegioUninominaleSenatoLista extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_candidature_collegi_uninominale_senato_liste';
}
