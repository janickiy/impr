<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagsVideos extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'tags_videos';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag_id',
        'video_id',
    ];
}
