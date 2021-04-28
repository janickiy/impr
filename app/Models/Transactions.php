<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';

    protected $table = 'transactions';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'status',
        'user_id',
    ];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne(Users::class,'id','user_id');
    }
}
