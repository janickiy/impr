<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'videos';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'videoalbum_id',
        'src',
        'properties json',
        'user_id',
        'preview1',
        'preview2',
        'preview3',
        'moderate_at',
        'banned_at',
    ];
}
