<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $table = 'amocrm_notes';
    protected $fillable = [
            'note_id',
            'entity_id',
            'created_by',
            'updated_by',
            'created_at_amocrm',
            'updated_at_amocrm',
            'responsible_user_id',
            'group_id',
            'note_type',
            'params',
            'account_id',
            'raw',
        ],

        $casts = [
            'note_id' => 'integer',
            'entity_id' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'created_at_amocrm' => 'datetime',
            'updated_at_amocrm' => 'datetime',
            'responsible_user_id' => 'integer',
            'group_id' => 'integer',
            'note_type' => 'string',
            'params' => 'array',
            'account_id' => 'integer',
            'raw' => 'array',
        ];

    public static function getLastUpdate()
    {
        // Получаем последнюю запись по дате обновления
        $last = self::orderBy('updated_at_amocrm', 'DESC')->first()->updated_at_amo ?? null;
        return $last ? now()->parse($last) : null;
    }
}
