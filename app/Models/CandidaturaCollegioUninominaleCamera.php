<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CandidaturaCollegioUninominaleCamera extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidature_collegi_uninominale_camera';

    public function liste(): BelongsToMany
    {
        return $this->belongsToMany(Lista::class, 'candidature_collegi_uninominale_camera_liste', 'candidatura_collegio_uninominale_camera_id', 'lista_id');
    }

    public function candidato(): BelongsTo
    {
        return $this->belongsTo(Candidato::class, 'candidato_id');
    }

    public function coalizione(): BelongsTo
    {
        return $this->belongsTo(Coalizione::class, 'coalizione_id');
    }

    public function collegio(): BelongsTo
    {
        return $this->belongsTo(CollegioUninominaleCamera::class, 'collegio_uninominale_camera_id');
    }

}
