<?php

namespace App\Traits;

use Watson\Rememberable\Rememberable;

trait Cache
{
    use Rememberable;

    protected $rememberCacheTag = __CLASS__;

    public function getTag()
    {
        return $this->rememberCacheTag;
    }
}
