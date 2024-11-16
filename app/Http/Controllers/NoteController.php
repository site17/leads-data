<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class NoteController extends Controller
{
    public function index(Request $request, $lead_id)
    {
        // авторизованный пользователь
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }
        $user = User::where('id', $user->id)->first();
        $lead_id_from_leads = Lead::where('id', $lead_id)->first();
        // Инициализируем коллекцию 
        $notes = Note::where('entity_id', $lead_id_from_leads->lead_id)
            ->orderBy('created_at_amocrm', 'desc')
            ->select('created_at_amocrm', 'note_type', 'params')  // Выбираем нужные поля
            ->get();  // Получаем коллекцию

        // Преобразуем поле params в строку
        $notes->transform(function ($note) {
            $note->params = json_encode($note->params);
            return $note;
        });
        // Возвращаем данные в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $notes,
            'lead' => $lead_id_from_leads->lead_id,
        ]);
    }
}
