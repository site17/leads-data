<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statuses extends Model
{
    use HasFactory;
    protected $table = 'statuses';

    protected $fillable = [
        'status_id',
        'pipeline_pid',
        'pipeline_id', //id из amoCrm
        'name',
        'sort',
        'is_editable',
        'color',
        'type',
        'account_id',
        'raw',
    ];

    protected $casts = [
        'status_id' => 'integer',      // Явное указание типа для ID статуса
        'pipeline_pid' => 'integer',   // Тип для ID воронки
        'sort' => 'integer',           // Порядок сортировки как целое число
        'is_editable' => 'boolean',    // Преобразование в boolean
        'type' => 'integer',           // Тип статуса как целое число
        'account_id' => 'integer',     // ID аккаунта как целое число
        'raw' => 'array',              // Преобразование JSON в массив
    ];

    // Связь с моделью Pipeline
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id', 'pipeline_id');
    }
}
