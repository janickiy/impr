<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'share';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id',
        'user_id',
    ];

    /**
     * @return hasOne
     */
    public function user(): hasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    /**
     * @return hasOne
     */
    public function video(): hasOne
    {
        return $this->hasOne(Video::class,'id','video_id');
    }
}
