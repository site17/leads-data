<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'amocrm_events';

    protected
        $fillable = [
            'event_id',
            'type',
            'entity_id',
            'lead_pid',
            'entity_type',
            'created_by',
            'created_at_amo',
            'value_after',
            'value_before',
            'account_id',
            'raw',
        ],
        $casts = [
            'event_id' => 'string',
            'type' => 'string',
            'entity_id' => 'integer',
            'lead_pid' => 'integer',
            'entity_type' => 'string',
            'created_by' => 'integer',
            'created_at_amo' => 'datetime',
            'value_after' => 'array',
            'value_before' => 'array',
            'account_id' => 'integer',
            'raw' => 'array',
        ];
}
