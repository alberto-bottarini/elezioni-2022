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

    public $table = 'collegi_uninominali_camera';

    public function collegioPlurinominale()
    {
        return $this->belongsTo(CollegioPlurinominaleCamera::class, 'collegio_plurinominale_id');
    }

    public function comuni()
    {
        return $this->hasMany(Comune::class, 'collegio_uninominale_camera_id')
            ->orderBy('nome');
    }

    public function candidature(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleCamera::class, 'collegio_uninominale_camera_id')
            ->orderBy('numero');
    }

    protected function getDetailRouteName(): string {
        return 'collegio_uninominale_camera';
    }
    
}
