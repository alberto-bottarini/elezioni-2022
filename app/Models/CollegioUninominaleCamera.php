<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollegioUninominaleCamera extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'collegi_uninominali_camera';

    public function collegioPlurinominale()
    {
        return $this->belongsTo(CollegioPlurinominaleCamera::class, 'collegio_plurinominale_id');
    }

    public function comuni()
    {
        return $this->belongsToMany(Comune::class, 'collegi_uninominali_camera_comuni', 'collegio_uninominale_camera_id', 'comune_id')
            ->orderBy('nome');
    }

    public function candidature(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleCamera::class, 'collegio_uninominale_camera_id')
            ->orderBy('numero');
    }

    public function risultati(): HasMany
    {
        return $this->hasMany(RisultatoCollegioUninominaleCamera::class, 'collegio_id')
            ->orderByDesc('voti');
    }

    public function risultatiListe(): HasMany
    {
        return $this->hasMany(RisultatoCollegioUninominaleCameraLista::class, 'collegio_id')
            ->orderByDesc('voti');
    }

    protected function getDetailRouteName(): string
    {
        return 'collegio_uninominale_camera';
    }
}
