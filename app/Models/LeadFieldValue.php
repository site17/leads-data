<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFieldValue extends Model
{
    use HasFactory;
    // Определите таблицу, с которой будет работать модель
    protected $table = 'lead_fields_value';

    // Укажите поля, которые могут быть массово присвоены (mass assignable)
    protected $fillable = [
        'lead_id',
        'field_id',
        'value',
    ];

    // Определите связь с моделью Lead
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public static function getLastUpdate()
    {
        // Получаем последнюю запись по дате обновления
        $last = self::orderBy('created_at', 'DESC')->first()->created_at ?? null;
        return $last ? now()->parse($last) : null;
    }
}
