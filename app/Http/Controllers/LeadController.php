<?php


namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\userRight;
use App\Models\LeadFields;
use Illuminate\Http\Request;
use App\Models\LeadFieldValue;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }
        $user = User::where('id', $user->id)->first();
        $user_rights = userRight::where('user_pid', $user->id)->first();
        if (!$user_rights) {
            return response()->json(['status' => 'error', 'message' => 'No user rights found'], 403);
        }

        // Инициализируем массив с лидами
        $leads = collect();
        // $lead_field_ids = collect();


        if ($user_rights->role_pid == 6) {
            //Проверка на маркетолога или телемаркетолога
            //'Utm_source'
            $lead_field_ids = LeadFields::where('name', 'Utm_medium')->pluck('custom_field_id')->toArray();
            $lead_id_by_values = LeadFieldValue::whereIn('field_id', $lead_field_ids)
                ->where('value', $user_rights->utm_medium)->pluck('lead_id')->toArray();

            $leads = Lead::whereIn('id', $lead_id_by_values)->where('created_at_amo', '>=', Carbon::create(2024, 8, 31, 0, 0, 0))->select(['id', 'lead_id', 'status_id', 'pipeline_id', 'price', 'name', 'created_at_amo'])->orderBy('created_at_amo', 'desc')->paginate(50); //Lead::all();
            // Извлекаем массив id всех загруженных лидов
            $leadIds = $leads->pluck('id')->toArray();

            // Второй запрос: догружаем поле raw_content для загруженных лидов
            $leadsWithRawContent = Lead::whereIn('id', $leadIds)
                ->select(['id', 'raw_content']) // Загружаем только id и raw_content
                ->get();

            // Объединяем данные: добавляем raw_content в основной массив лидов
            $leads->transform(function ($lead) use ($leadsWithRawContent) {
                // Находим соответствующий raw_content по id лида
                $leadWithContent = $leadsWithRawContent->firstWhere('id', $lead->id);
                $lead->raw_content = $leadWithContent ? $leadWithContent->raw_content : null; // Добавляем raw_content
                return $lead;
            });
            // Преобразуем поле raw_content в строку
            $leads->transform(function ($lead) {
                $lead->raw_content = json_encode($lead->raw_content);
                return $lead;
            });
        } elseif ($user_rights->role_pid == 4) {
            //проверка для тимлида
            $team_utm_arr = userRight::where('team_leader_utm_medium', $user_rights->utm_medium)->pluck('utm_medium')->toArray();
            $lead_field_ids = LeadFields::where('name', 'Utm_medium')->pluck('custom_field_id')->toArray();
            array_push($team_utm_arr, $user_rights->utm_medium); //добавляем utm тимлида
            $lead_id_by_values = LeadFieldValue::whereIn('field_id', $lead_field_ids)
                ->whereIn('value', $team_utm_arr)->pluck('lead_id')->toArray();
            $leads = Lead::whereIn('id', $lead_id_by_values)->where('created_at_amo', '>=', Carbon::create(2024, 8, 31, 0, 0, 0))->select(['id', 'lead_id', 'status_id', 'pipeline_id', 'price', 'name', 'created_at_amo'])->orderBy('created_at_amo', 'desc')->paginate(50); //Lead::all();
            // Извлекаем массив id всех загруженных лидов
            $leadIds = $leads->pluck('id')->toArray();

            // Второй запрос: догружаем поле raw_content для загруженных лидов
            $leadsWithRawContent = Lead::whereIn('id', $leadIds)
                ->select(['id', 'raw_content']) // Загружаем только id и raw_content
                ->get();

            // Объединяем данные: добавляем raw_content в основной массив лидов
            $leads->transform(function ($lead) use ($leadsWithRawContent) {
                // Находим соответствующий raw_content по id лида
                $leadWithContent = $leadsWithRawContent->firstWhere('id', $lead->id);
                $lead->raw_content = $leadWithContent ? $leadWithContent->raw_content : null; // Добавляем raw_content
                return $lead;
            });
            // Преобразуем поле raw_content в строку
            $leads->transform(function ($lead) {
                $lead->raw_content = json_encode($lead->raw_content);
                return $lead;
            });
        } elseif ($user_rights->role_pid == 1) {
            //проверка для админа
            $leads = Lead::select(['id', 'lead_id', 'status_id', 'pipeline_id', 'price', 'name', 'created_at_amo'])
                ->where('created_at_amo', '>=', Carbon::create(2024, 8, 31, 0, 0, 0))
                ->orderBy('created_at_amo', 'desc')
                ->paginate(50);
            // Извлекаем массив id всех загруженных лидов
            $leadIds = $leads->pluck('id')->toArray();

            // Второй запрос: догружаем поле raw_content для загруженных лидов
            $leadsWithRawContent = Lead::whereIn('id', $leadIds)
                ->select(['id', 'raw_content']) // Загружаем только id и raw_content
                ->get();

            // Объединяем данные: добавляем raw_content в основной массив лидов
            $leads->transform(function ($lead) use ($leadsWithRawContent) {
                // Находим соответствующий raw_content по id лида
                $leadWithContent = $leadsWithRawContent->firstWhere('id', $lead->id);
                $lead->raw_content = $leadWithContent ? $leadWithContent->raw_content : null; // Добавляем raw_content
                return $lead;
            });
            // Преобразуем поле raw_content в строку
            $leads->transform(function ($lead) {
                $lead->raw_content = json_encode($lead->raw_content);
                return $lead;
            });
            // $leads = ['1'];
        } else {
            $leads = [];
        }
        // Возвращаем данные в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $leads,



        ]);
    }

    public function getLeadById($lead_id)
    {
        // Получаем лид из базы данных
        // $leads = Lead::where('id', $lead_id)->first();

        // // Преобразуем поле raw_content в строку
        // $leads->raw_content = json_encode($leads->raw_content);

        // // Возвращаем данные в формате JSON
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $leads,
        // ]);
        // Получаем лид из базы данных вместе с его контактами
        $lead = Lead::with('contacts')->where('lead_id', $lead_id)->first();

        // Проверка на случай, если лид не найден
        if (!$lead) {
            return response()->json([
                'status' => 'error',
                'message' => 'Лид не найден',
            ], 404);
        }

        // Преобразуем поле raw_content в строку
        $lead->raw_content = json_encode($lead->raw_content);

        // Возвращаем данные в формате JSON, включая контакты
        return response()->json([
            'status' => 'success',
            'data' => $lead,
        ]);
    }
    // $lead = Lead::with('contacts')->find($leadId);
    public function getLeadId($id)
    {
        // Получаем лид из базы данных
        $leads = Lead::where('id', $id)->first();

        // Преобразуем поле raw_content в строку
        $leads->raw_content = json_encode($leads->raw_content);

        // Возвращаем данные в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $leads,
        ]);
    }
}
