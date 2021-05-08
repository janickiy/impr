<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'comments';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id',
        'user_id',
        'comment',
        'parent_id',
    ];

    /**
     * @return mixed
     */
    public function video(): hasOne
    {
        return $this->hasOne(Videos::class,'id','video_id');
    }

    /**
     * @return mixed
     */
    public function user(): hasOne
    {
        return $this->hasOne(Users::class,'id','user_id');
    }

    /**
     * @return mixed
     */
    public function parent(): hasOne
    {
        return $this->hasOne($this, 'id', 'parent_id');
    }

    /**
     * @return mixed
     */
    public function children(): hasMany
    {
        return $this->hasMany($this, 'parent_id', 'id');
    }

}
