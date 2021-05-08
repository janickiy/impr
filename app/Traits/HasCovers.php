<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\morphToMany;

trait HasCovers
{
    /**
     * @return morphToMany
     */
    public function covers(): morphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }
}
