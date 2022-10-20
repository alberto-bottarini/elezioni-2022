<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Coalizione extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'coalizioni';

    public function candidatureCollegiUninominaliCamera(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleCamera::class, 'coalizione_id');
    }

    public function candidatureCollegiUninominaliSenato(): HasMany
    {
        return $this->hasMany(CandidaturaCollegioUninominaleSenato::class, 'coalizione_id');
    }

    public function liste(): HasMany
    {
        return $this->hasMany(Lista::class, 'coalizione_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'coalizione';
    }
}
