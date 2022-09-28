<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CandidaturaCollegioPlurinominaleSenato extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'candidature_collegi_plurinominali_senato';

    public function lista(): BelongsTo
    {
        return $this->belongsTo(Lista::class, 'lista_id');
    }

    public function collegioPlurinominale(): BelongsTo
    {
        return $this->belongsTo(CollegioPlurinominaleSenato::class, 'collegio_plurinominale_senato_id');
    }

    public function candidati(): BelongsToMany
    {
        return $this->belongsToMany(Candidato::class, 'candidati_candidature_collegi_plurinominali_camera', 'candidatura_collegio_plurinominale_camera_id', 'candidato_id')
            ->orderBy('numero');
    }
}
