<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircoscrizioneSenato extends Model
{
    use HasFactory;
    use Sluggable;

    public $timestamps = false;
    public $table = 'circoscrizioni_senato';

    public function collegiPlurinominali()
    {
        return $this->hasMany(CollegioPlurinominaleSenato::class, 'circoscrizione_id');
    }

    protected function getDetailRouteName(): string
    {
        return 'circoscrizione_senato';
    }
}
