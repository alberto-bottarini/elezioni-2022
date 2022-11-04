<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NazioneEstero extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'nazioni_estero';

    public function ripartizione(): BelongsTo
    {
        return $this->belongsTo(RipartizioneEstero::class, 'ripartizione_id');
    }

    public function preferenzeCamera(): HasMany
    {
        return $this->hasMany(PreferenzaEsteroCamera::class, 'nazione_id');
    }

    public function preferenzeSenato(): HasMany
    {
        return $this->hasMany(PreferenzaEsteroSenato::class, 'nazione_id');
    }

    public function votiCamera(): HasMany
    {
        return $this->hasMany(VotoListaEsteroCamera::class, 'nazione_id')
            ->orderByDesc('voti');
    }

    public function votiSenato(): HasMany
    {
        return $this->hasMany(VotoListaEsteroSenato::class, 'nazione_id')
            ->orderByDesc('voti');
    }

    protected function getDetailRouteName(): string
    {
        return 'nazione_estero';
    }
}
