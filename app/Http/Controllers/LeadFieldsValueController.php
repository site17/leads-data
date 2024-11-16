<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadFieldValue;

class LeadFieldsValueController extends Controller
{
    public function getFieldsByLeadIds(Request $request)
    {
        // Получаем массив lead_id из запроса
        $leadIds = $request->input('lead_ids');
        // Проверка на корректность входных данных
        if (empty($leadIds) || !is_array($leadIds)) {
            return response()->json(['error' => 'Invalid lead_ids'], 400);
        }

        // Получаем поля для указанных lead_id
        $fields = LeadFieldValue::whereIn('lead_id', $leadIds)->get();

        // Возвращаем результат в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $fields,

        ]);
        // return response()->json($fields);
    }
}
