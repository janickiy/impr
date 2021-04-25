<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'pgsql';

    protected $table = 'users';

    const TYPE_USER = 0;
    const TYPE_PRO = 1;

    public static $type_name = [
        self::TYPE_USER => 'пользователь',
        self::TYPE_PRO => 'профи',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'password',
        'firstname',
        'lastname',
        'secondname',
        'about',
        'birthday',
        'gender',
        'contacts',
        'settings',
        'role',
        'provider',
        'banned_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static $contactsValidationMessages = [
        'email.required' => 'E-mail не может быть пустым',
        'email.email' => 'Контактный E-mail указан неверно',
        'firstname.required' => 'Имя не может быть пустым',
        'firstname.required' => 'Фамилия не может быть пустой',
    ];

    public $validationErrors;

    /**
     * @return array
     */
    protected static function getContactsValidationRules()
    {
        return [
            'email' => 'required|email',
            'firstname' => 'required',
            'firstname' => 'required'
        ];
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
     * @param $data
     * @return bool
     */
    public function validateContacts($data)
    {
        $validator = Validator::make($data, static::getContactsValidationRules(), static::$contactsValidationMessages);

        if ($validator->fails()) {
            $this->validationErrors = $validator->errors()->toArray();
            return false;
        }

        return true;
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
}
