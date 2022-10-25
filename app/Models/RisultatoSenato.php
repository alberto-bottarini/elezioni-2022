<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RisultatoSenato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'risultati_senato';

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }
}
