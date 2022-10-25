<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RisultatoCircoscrizioneSenato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_circoscrizioni_senato';

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    public function circoscrizione(): BelongsTo
    {
        return $this->belongsTo(CircoscrizioneSenato::class, 'circoscrizione_id');
    }
}
