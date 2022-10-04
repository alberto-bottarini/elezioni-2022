<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lista extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'liste';

    protected function getDetailRouteName(): string
    {
        return 'lista';
    }

    public function candidatureCollegiUninominaliCamera(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioUninominaleCamera::class, 'candidature_collegi_uninominale_camera_liste', 'lista_id', 'candidatura_collegio_uninominale_camera_id');
    }

    public function candidatureCollegiPlurinominaliCamera(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioPlurinominaleCamera::class, 'lista_id');
    }

    public function candidatureCollegiUninominaliSenato(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioUninominaleSenato::class, 'candidature_collegi_uninominale_senato_liste', 'lista_id', 'candidatura_collegio_uninominale_senato_id');
    }

    public function candidatureCollegiPlurinominaliSenato(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioPlurinominaleSenato::class, 'lista_id');
    }
}
