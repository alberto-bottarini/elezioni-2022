<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferenzaEsteroCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'preferenze_estero_camera';

    public function candidatura(): BelongsTo
    {
        return $this->belongsTo(CandidaturaEsteroCamera::class, 'candidatura_id');
    }

}
