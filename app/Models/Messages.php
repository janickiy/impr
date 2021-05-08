<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'messages';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'status',
    ];

    /**
     * @return mixed
     */
    public function sender(): hasOne
    {
        return $this->hasOne(Users::class,'id','sender_id');
    }

    /**
     * @return mixed
     */
    public function receiver(): hasOne
    {
        return $this->hasOne(Users::class,'id','receiver_id');
    }
}
