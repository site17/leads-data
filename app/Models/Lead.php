<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';

    protected
        $fillable = [
            'name',
            'lead_id',
            'group_id',
            'account_id',
            'pipeline_id',
            'status_id',
            'company_id',
            'price',
            'raw_content',
            'responsible_user_id',
            'integration_id',
            'pipeline_pid',
            'group_id',
            'created_at_amo',
            'updated_at_amo',
            'closed_at',
            'is_deleted',
        ],
        $casts = [
            'name' => 'string',
            'lead_id' => 'integer',
            'group_id' => 'integer',
            'account_id' => 'integer',
            'pipeline_id' => 'integer',
            'status_id' => 'integer',
            'company_id' => 'integer',
            'price' => 'integer',
            'raw_content' => 'array',
            'created_at_amo' => 'datetime',
            'updated_at_amo' => 'datetime',
            'closed_at' => 'datetime',
            'is_deleted' => 'boolean',
        ];
    // Мутатор: при установке значения в raw_content оно будет декодироваться из строки в массив
    // public function setRawContentAttribute($value)
    // {
    //     // Если значение является строкой, то пытаемся декодировать его в массив
    //     $this->attributes['raw_content'] = is_string($value) ? json_decode($value, true) : $value;
    // }

    // Аксессор: при получении значения raw_content оно будет преобразовано в строку
    public function getRawContentAttribute($value)
    {
        // Если значение является строкой (JSON), декодируем его
        return is_string($value) ? json_decode($value, true) : $value;
        // return json_encode($value, true);
    }

    public static function findByLeadId($id)
    {
        return Lead::whereLeadId($id)->first();
    }

    public function pipeline()
    {
        return $this->hasOne(Pipeline::class, 'pipeline_id', 'pipeline_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'account_id', 'account_id');
    }
    // Связь с моделью Contact через промежуточную таблицу
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contacts_lead', 'lead_pid', 'contact_pid')
            ->withTimestamps();
    }

    public static function getLastUpdate()
    {
        // Получаем последнюю запись по дате обновления
        $last = self::orderBy('updated_at_amo', 'DESC')->first()->updated_at_amo ?? null;
        return $last ? now()->parse($last) : null;
    }
}
