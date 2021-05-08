<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    /**
     * @return morphToMany
     */
    public function tags(): morphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
