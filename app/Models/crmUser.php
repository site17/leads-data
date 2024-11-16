<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class crmUser extends Model
{
    protected $table = 'crm_user';
    protected
        $fillable = [
            'user_id',
            'name',
            'email',
            'rights',
            'raw',
        ],
        $casts = [
            'user_id' => 'integer',
            'name' => 'string',
            'email' => 'string',
            'rights' => 'array',
            'raw' => 'array',
        ];

    public static function findByUserId($id)
    {
        return crmUser::whereUserId($id)->first();
    }
}
