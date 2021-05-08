<?php

namespace App\Models;

use Eloquent;
use App\Traits\HasTags;
use Illuminate\Support\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Пользователь.
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $email
 * @property string|null $password
 * @property int $gender
 * @property string|null $birthdate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $social_id
 * @property string|null $social_type
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $permissions_count
 * @property-read Collection|Post[] $posts
 * @property-read int|null $posts_count
 * @property-read int|null $roles_count
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read int|null $tokens_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereBirthdate($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLogin($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereSocialId($value)
 * @method static Builder|User whereSocialType($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasTags;

    public const GENDER_MALE = 1;

    public const GENDER_FEMALE = 2;

    public const GENDER_UNISEX = 3;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    public static $type_name = [
        self::GENDER_MALE => 'муж',
        self::GENDER_FEMALE => 'жен',
        self::GENDER_UNISEX => 'нет',
    ];

    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'password',
        'nickname',
        'firstname',
        'lastname',
        'about',
        'birthday',
        'gender',
        'contacts',
        'settings',
        'role',
        'provider',
        'banned_at',
    ];

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @param $value
     */
    public function setContactsAttribute($value)
    {
        $this->attributes['contacts'] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getContactsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     */
    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getSettingsAttribute($value)
    {
        return json_decode($value);
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * @return string
     */
    public function fullname()
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
