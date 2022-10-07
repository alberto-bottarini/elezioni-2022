<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comune extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'comuni';

    public function collegiUninominaliCamera(): BelongsToMany
    {
        return $this->belongsToMany(CollegioUninominaleCamera::class, 'collegi_uninominali_camera_comuni', 'comune_id', 'collegio_uninominale_camera_id');
    }

    public function collegiUninominaliSenato(): BelongsToMany
    {
        return $this->belongsToMany(CollegioUninominaleSenato::class, 'collegi_uninominali_senato_comuni', 'comune_id', 'collegio_uninominale_senato_id');
    }

    public function affluenza(): HasOne
    {
        return $this->hasOne(ComuneAffluenza::class, 'comune_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'comune';
    }

    public function nomeCompleto(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->nome . ' (' . $this->provincia . ')'
        );
    }
}
