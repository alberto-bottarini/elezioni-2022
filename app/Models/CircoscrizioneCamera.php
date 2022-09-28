<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;


class CircoscrizioneCamera extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'circoscrizioni_camera';

    public function collegiPlurinominali()
    {
        return $this->hasMany(CollegioPlurinominaleCamera::class, 'circoscrizione_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'circoscrizione_camera';
    }
}
