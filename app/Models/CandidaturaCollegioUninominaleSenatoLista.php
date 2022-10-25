<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidaturaCollegioUninominaleSenatoLista extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidature_collegi_uninominale_senato_liste';

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    public function voti(): HasMany
    {
        return $this->hasMany(VotoCandidaturaSenatoComuneLista::class, 'candidatura_lista_id');
    }

    public function candidatura(): BelongsTo
    {
        return $this->belongsTo(CandidaturaCollegioUninominaleSenato::class, 'candidatura_collegio_uninominale_senato_id');
    }
}
