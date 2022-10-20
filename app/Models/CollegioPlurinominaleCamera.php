<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollegioPlurinominaleCamera extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'collegi_plurinominali_camera';

    public function circoscrizione()
    {
        return $this->belongsTo(CircoscrizioneCamera::class, 'circoscrizione_id');
    }

    public function collegiUninominali(): HasMany
    {
        return $this->hasMany(CollegioUninominaleCamera::class, 'collegio_plurinominale_id');
    }

    public function candidature(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioPlurinominaleCamera::class, 'collegio_plurinominale_camera_id')
            ->orderBy('numero');
    }

    public function risultati(): HasMany
    {
        return $this->hasMany(RisultatoCollegioPlurinominaleCamera::class, 'collegio_id')
            ->orderByDesc('voti');
    }

    protected function getDetailRouteName(): string
    {
        return 'collegio_plurinominale_camera';
    }
}
