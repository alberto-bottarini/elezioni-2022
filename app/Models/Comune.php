<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comune extends Model
{
    use HasFactory;
    use Sluggable;

    public $table = 'comuni';

    public function collegioUninominaleCamera()
    {
        return $this->belongsTo(CollegioUninominaleCamera::class, 'collegio_uninominale_camera_id');
    }

    public function collegioUninominaleSenato()
    {
        return $this->belongsTo(CollegioUninominaleSenato::class, 'collegio_uninominale_senato_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'comune';
    }
}
