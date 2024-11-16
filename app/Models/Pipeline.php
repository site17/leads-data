<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;
    protected $table = 'pipeline';
    protected
        $fillable = [
            'pipeline_id',
            'name',
            'sort',
            'account_id',
            'is_main',
            'is_unsorted_on',
            'is_archive',
            'raw',
        ],
        $casts = [
            'pipeline_id' => 'integer',
            'name' => 'string',
            'sort' => 'integer',
            'account_id' => 'integer',
            'is_main' => 'boolean',
            'is_unsorted_on' => 'boolean',
            'is_archive' => 'boolean',
            'raw' => 'array',
        ];

    public static function findByPipelineId($id)
    {
        return Pipeline::wherePipelineId($id)->first();
    }
}
