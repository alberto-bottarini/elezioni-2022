<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidaturaEsteroCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidature_estero_camera';

    public function ripartizione(): BelongsTo
    {
        return $this->belongsTo(RipartizioneEstero::class, 'ripartizione_id');
    }

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class, 'candidato_id');
    }
}
