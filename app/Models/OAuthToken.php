<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OAuthToken extends Model
{
    use HasFactory;

    // Указываем имя таблицы, если оно отличается от стандартного
    protected $table = 'oauth_tokens';

    // Указываем, какие поля могут быть массово заполнены
    protected $fillable = [
        'service',
        'access_token',
        'refresh_token',
        'expires_in',
        'client_id',
        'client_secret',
        'redirect_uri',
        'subdomain',
    ];

    // Указываем, какие поля должны быть приведены к типам
    protected $casts = [
        'expires_in' => 'datetime',  // Приводим поле к типу datetime
    ];

    // Можно добавить методы для работы с токенами, например:
    public static function getToken($service)
    {
        return self::where('service', $service)->first();
    }

    // Проверка истечения срока действия токена
    public function isExpired()
    {
        return $this->expires_in < now();
    }

    // Метод для обновления или создания записи
    public static function updateOrCreateToken(array $attributes, array $values = [])
    {
        // Ищем запись по атрибутам
        $instance = static::where($attributes)->first();

        if ($instance) {
            // Обновляем найденную запись
            $instance->update($values);
        } else {
            // Создаем новую запись
            $instance = static::create(array_merge($attributes, $values));
        }

        return $instance;
    }
}