<?php

namespace App\Models\Auth;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Подтверждение email.
 *
 * @property string $email
 * @property string $token
 * @property Carbon|null $created_at
 * @method static Builder|EmailConfirm newModelQuery()
 * @method static Builder|EmailConfirm newQuery()
 * @method static Builder|EmailConfirm query()
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|EmailConfirm whereEmail($value)
 * @method static Builder|EmailConfirm whereToken($value)
 * @mixin Eloquent
 */
class EmailConfirm extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    /**
     * @var string[]
     */
    protected $fillable = ['email', 'token'];

    /**
     * @var null
     */
    protected $primaryKey = 'token';

    /**
     * @var bool
     */
    public $incrementing = false;
}
