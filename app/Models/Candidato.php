<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidati';

    public function candidatureCollegiPlurinominaliCamera(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleCamera::class, 'candidati_candidature_collegi_plurinominali_camera', 'candidato_id', 'candidatura_collegio_plurinominale_camera_id');
    }

    public function candidatureCollegiUninominaliCamera(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleCamera::class, 'candidato_id');
    }

    public function candidatureCollegiPlurinominaliSenato(): BelongsToMany
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleSenato::class, 'candidati_candidature_collegi_plurinominali_senato', 'candidato_id', 'candidatura_collegio_plurinominale_senato_id');
    }

    public function candidatureCollegiUninominaliSenato(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleSenato::class, 'candidato_id');
    }

    public function scopeOrderByListaName(Builder $query)
    {
        $query->select('candidati.*')
            ->join('liste', 'liste.id', 'candidati.lista_id')
            ->orderBy('liste.nome');
    }

    public function nomeCompleto(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->cognome . ' ' . $this->nome . ' ' . $this->altro_1 . ' ' . $this->altro_2
        );
    }
}
