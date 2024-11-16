<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'contacts';
    protected $fillable = [
        'contact_id',
        'responsible_user_id',
        'group_id',
        'account_id',
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'custom_fields',
        'raw_content',
        'created_at_amo',
        'updated_at_amo',
    ];

    protected $casts = [
        'contact_id' => 'integer',
        'responsible_user_id' => 'integer',
        'group_id' => 'integer',
        'account_id' => 'integer',
        'name' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'custom_fields' => 'string',
        'raw_content' => 'array',
        'created_at_amo' => 'datetime',
        'updated_at_amo' => 'datetime',
    ];

    // Связь с моделью Lead через промежуточную таблицу
    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'contacts_lead', 'contact_pid', 'lead_pid')
            ->withTimestamps();
    }
    public static function getContactUpdate()
    {
        // Получаем последнюю запись по дате обновления
        $last = self::orderBy('updated_at_amo', 'DESC')->first()->updated_at_amo ?? null;
        return $last ? now()->parse($last) : null;
    }
    public function getRawContentAttribute($value)
    {
        // Если значение является строкой (JSON), декодируем его
        return is_string($value) ? json_decode($value, true) : $value;
    }
    // public function getCustomFieldsAttribute($value)
    // {
    //     // Если значение является строкой (JSON), декодируем его
    //     return is_string($value) ? json_decode($value, true) : $value;
    // }
}
