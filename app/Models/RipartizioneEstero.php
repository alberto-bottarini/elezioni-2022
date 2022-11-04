<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RipartizioneEstero extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'ripartizioni_estero';

    protected function getDetailRouteName(): string
    {
        return 'ripartizione_estero';
    }

    public function nazioni(): HasMany
    {
        return $this->hasMany(NazioneEstero::class, 'ripartizione_id');
    }

    public function candidatureCamera(): HasMany
    {
        return $this->hasMany(CandidaturaEsteroCamera::class, 'ripartizione_id');
    }

    public function candidatureSenato(): HasMany
    {
        return $this->hasMany(CandidaturaEsteroSenato::class, 'ripartizione_id');
    }
}
