<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $int)
 * @property mixed $lat
 * @property mixed $lon
 * @property mixed $city
 */
class City extends Model
{
    protected $fillable = ['city', 'state', 'φ(d)', 'λ(d)'];

    public function getLatAttribute()
    {
        return $this->attributes['φ(d)'];
    }

    public function getLonAttribute()
    {
        return $this->attributes['λ(d)'];
    }


}

