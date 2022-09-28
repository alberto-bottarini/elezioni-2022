<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegioUninominaleSenato extends Model
{
    use HasFactory;

    public $table = 'collegi_uninominali_senato';

    public function collegioPlurinominale()
    {
        return $this->belongsTo(CollegioPlurinominaleSenato::class, 'collegio_plurinominale_id');
    }

    public function comuni()
    {
        return $this->hasMany(Comune::class, 'collegio_uninominale_senato_id');
    }
}
