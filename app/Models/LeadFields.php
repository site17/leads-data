<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFields extends Model
{
    use HasFactory;
    protected $table = 'lead_fields';

    protected
        $fillable = [
            'name',
            'custom_field_id',
            'code',
            'sort',
            'type',
            'entity_type',
            'is_predefined',
            'settings',
            'remind',
            'is_api_only',
            'group_id',
            'required_statuses',
            'currency',
            'enums',
            'raw'
        ],
        $casts = [
            'name' => 'string',
            'custom_field_id' => 'integer',
            'status_id' => 'integer',
            'required_statuses' => 'array',
            'enums' => 'array',
            'raw' => 'array',
        ];
}
