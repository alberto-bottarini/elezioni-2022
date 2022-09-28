<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollegioPlurinominaleSenato extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'collegi_plurinominali_senato';

    public function circoscrizione(): BelongsTo
    {
        return $this->belongsTo(CircoscrizioneSenato::class, 'circoscrizione_id');
    }
    
    public function collegiUninominali(): HasMany
    {
        return $this->hasMany(CollegioUninominaleSenato::class, 'collegio_plurinominale_id');
    }

    public function candidature(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioPlurinominaleSenato::class, 'collegio_plurinominale_senato_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'collegio_plurinominale_camera';
    }
}
