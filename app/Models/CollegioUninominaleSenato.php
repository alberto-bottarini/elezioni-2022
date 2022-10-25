<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollegioUninominaleSenato extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'collegi_uninominali_senato';

    public function collegioPlurinominale()
    {
        return $this->belongsTo(CollegioPlurinominaleSenato::class, 'collegio_plurinominale_id');
    }

    public function comuni()
    {
        return $this->belongsToMany(Comune::class, 'collegi_uninominali_senato_comuni', 'collegio_uninominale_senato_id', 'comune_id')
            ->orderBy('nome');
    }

    public function candidature(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleSenato::class, 'collegio_uninominale_senato_id')
            ->orderBy('numero');
    }

    public function risultati(): HasMany
    {
        return $this->hasMany(RisultatoCollegioUninominaleSenato::class, 'collegio_id')
            ->orderByDesc('voti');
    }

    public function risultatiListe(): HasMany
    {
        return $this->hasMany(RisultatoCollegioUninominaleSenatoLista::class, 'collegio_id')
            ->orderByDesc('voti');
    }

    protected function getDetailRouteName(): string {
        return 'collegio_uninominale_senato';
    }
}
