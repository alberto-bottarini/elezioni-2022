<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            get: fn ($value) => trim($this->cognome . ' ' . $this->nome . ' ' . $this->altro_1 . ' ' . $this->altro_2)
        );
    }

    public function getRouteKey()
    {
        return Str::slug($this->nomeCompleto) . '-' . $this->id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $parts = explode('-', $value);
        $id = array_pop($parts);
        $slug = implode('-', $parts);

        $model = self::where('id', $id)->firstOrFail();
        if (Str::slug($model->nomeCompleto) != $slug) {
            throw new HttpResponseException(redirect(route($this->getDetailRouteName(), $model)));
        }

        return $model;
    }
    
    protected function getDetailRouteName(): string
    {
        return 'candidato';
    }
}
