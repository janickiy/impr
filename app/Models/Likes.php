<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'likes';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content_type',
        'content_id',
    ];


    /**
     * @return hasOne
     */
    public function user(): hasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
