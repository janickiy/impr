# Установка

1. `cp .env.example .env` и заполнить необходимыми данными
2. `composer install`
3. `php artisan migrate`
4. `php artisan db:seed`
5. `php artisan key:generate`

# Нестандартные параметры `.env`

Параметр                           | Описание
-----------------------            | ----------------------------------------
`AWS_COLD_*`                       | конфиг s3 хранилища для оригиналов
`AWS_HOT_*`                        | конфиг s3 хранилища для медиа
`FACEBOOK_CLIENT_*`                | конфиг приложения facebook для авторизации
`VKONTAKTE_CLIENT_*`               | конфиг приложения vk для авторизации
`GOOGLE_CLIENT_*`                  | конфиг приложения google для авторизации

# PHPDoc

````
php artisan ide-helper:generate
php artisan ide-helper:models --write
php artisan ide-helper:meta
````

# Требования

- Redis
- FFMpeg
- PostgreSQL >= 10.16
- Composer
- PHP >= 7.4
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Pgsql PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Http PHP Extension
- Exif PHP Extension
- SOAP PHP Extension
- PCRE PHP Extension
- CURL PHP Extension
- Redis PHP Extension
