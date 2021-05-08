<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'complaint';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_type',
        'resource_id',
        'user_id',
        'message',
    ];

    /**
     * @return hasOne
     */
    public function user(): hasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
