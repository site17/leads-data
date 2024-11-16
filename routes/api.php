<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\LeadController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\LeadContactsController;
use App\Http\Controllers\LeadFieldsValueController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Группа защищенных маршрутов, которые требуют авторизации через Sanctum
Route::middleware(['sanctum', 'auth:sanctum'])->group(function () {
    // Защищенный маршрут для получения информации о текущем пользователе
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/user-info', [AuthController::class, 'userInfo']);
    Route::get('/contacts/{lead_id}', [LeadContactsController::class, 'getContctsByLeadId']);

    Route::get('/note/{lead_id}', [NoteController::class, 'index']);

    Route::get('/status/{status_id}', [StatusController::class, 'getStatusById']);
    Route::get('/status', [StatusController::class, 'getStatuses']);

    Route::post('/lead-fields', [LeadFieldsValueController::class, 'getFieldsByLeadIds']);
});
// Публичный маршрут для логина и генерации токена
Route::post('/login', [AuthController::class, 'login']);

//Этот маршрут будет обрабатывать POST-запросы на URL /api/lead-fields. Получаем кастомные поля равные lead_id
