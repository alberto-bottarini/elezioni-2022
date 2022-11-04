<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class Candidato extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'candidati';

    public function candidatureCollegiPlurinominaliCamera(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleCamera::class, 'candidati_candidature_collegi_plurinominali_camera', 'candidato_id', 'candidatura_collegio_plurinominale_camera_id')
            ->withPivot('eletto');
    }

    public function candidatureCollegiUninominaliCamera(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleCamera::class, 'candidato_id');
    }

    public function candidatureCollegiPlurinominaliSenato(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleSenato::class, 'candidati_candidature_collegi_plurinominali_senato', 'candidato_id', 'candidatura_collegio_plurinominale_senato_id')
            ->withPivot('eletto');
    }

    public function candidatureCollegiUninominaliSenato(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleSenato::class, 'candidato_id');
    }

    public function candidatureEsteroCamera(): HasMany
    {
        return $this->hasMany(CandidaturaEsteroCamera::class, 'candidato_id');
    }

    public function candidatureEsteroSenato(): HasMany
    {
        return $this->hasMany(CandidaturaEsteroSenato::class, 'candidato_id');
    }

    public function scopeOrderByListaName(Builder $query)
    {
        $query->select('candidati.*')
            ->join('liste', 'liste.id', 'candidati.lista_id')
            ->orderBy('liste.nome');
    }

    protected function getDetailRouteName(): string
    {
        return 'candidato';
    }
}
