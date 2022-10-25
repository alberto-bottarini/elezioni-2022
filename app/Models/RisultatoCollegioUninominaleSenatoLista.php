<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RisultatoCollegioUninominaleSenatoLista extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_collegi_uninominali_senato_liste';
}
