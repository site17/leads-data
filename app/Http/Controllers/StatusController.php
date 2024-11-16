<?php

namespace App\Http\Controllers;

use App\Models\Statuses;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    // Метод для получения статуса по status_id
    public function getStatusById($status_id)
    {
        // Найти статус по status_id
        $status = Statuses::where('status_id', $status_id)->first();

        // Проверяем, найден ли статус
        if (!$status) {
            return response()->json(['error' => 'Status not found'], 404);
        }

        // Возвращаем только название и цвет
        return response()->json([
            'name' => $status->name,
            'color' => $status->color,
        ]);
    }

    public function getStatuses()
    {
        $statuses = Statuses::All();

        if (!$statuses) {
            return response()->json(['error' => 'Status not found'], 404);
        }

        // Преобразуем коллекцию статусов в массив с необходимыми полями
        $statuses_data = $statuses->map(function ($status) {
            return (object)[
                'status_id' => $status->status_id,
                'name' => $status->name,
                'color' => $status->color
            ];
        });

        // Возвращаем данные в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $statuses_data // возвращаем массив статусов
        ]);
    }
}
