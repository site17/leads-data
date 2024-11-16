<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactLead;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class LeadContactsController extends Controller
{
    public function getContctsByLeadId($lead_id, Request $request)
    {
        // Получаем текущего авторизованного пользователя
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }
        $user = User::where('id', $user->id)->first();
        // Получаем id_contact по lead_id, потом получаем сам контакт
        $contact_lead = ContactLead::where('lead_pid', $lead_id)->first();

        if (!$contact_lead) {
            return response()->json([
                'status' => 'error',
                'message' => 'ContactLead not found for the given lead_id',
            ], 404);
        }

        $contact_id = $contact_lead->contact_pid;
        $contact = Contact::where('id', $contact_id)->first();

        if (!$contact) {
            return response()->json([
                'status' => 'error',
                'message' => 'Contact not found for the given contact_id',
                'contact_id'
                => $contact_id,
            ], 404);
        }

        // Преобразуем поле raw_content в строку
        $contact->raw_content = json_encode($contact->raw_content);

        // Возвращаем данные в формате JSON
        return response()->json([
            'status' => 'success',
            'data' => $contact,
        ]);
    }
}
