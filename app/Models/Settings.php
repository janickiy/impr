<?php

namespace App\Models;

use App\Traits\Cache;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use Cache;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'value',
    ];

    public function setNameAttribute($value) {
        $this->attributes['name'] = str_replace(' ', '_', $value);
    }
}
