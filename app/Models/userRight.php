<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userRight extends Model
{
    use HasFactory;
    protected $table = 'user_rights';

    protected
        $fillable = [
            'user_pid',
            'utm_source',
            'utm_medium',
            'role_pid',
            'is_active',
            'team_leader_utm_medium',
            'project_name',
        ],
        $casts = [
            'user_pid' => 'integer',
            'utm_source' => 'string',
            'utm_medium' => 'string',
            'role_pid' => 'integer',
            'is_active' => 'integer',
            'team_leader_utm_medium' => 'string',
            'project_name' => 'string',
        ];
}