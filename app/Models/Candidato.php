<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidati';

    public function candidatureCollegiPlurinominaliCamera()
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleCamera::class, 'candidati_candidature_collegi_plurinominali_camera', 'candidato_id', 'candidatura_collegio_plurinominale_camera_id');
    }

    public function candidatureCollegiPlurinominaliSenato()
    {
        return $this->belongsToMany(CandidaturaCollegioPlurinominaleSenato::class, 'candidati_candidature_collegi_plurinominali_senato', 'candidato_id', 'candidatura_collegio_plurinominale_senato_id');
    }

    public function lista()
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    public function scopeOrderByListaName(Builder $query)
    {
        $query->select('candidati.*')
            ->join('liste', 'liste.id', 'candidati.lista_id')
            ->orderBy('liste.nome');
    }
}
