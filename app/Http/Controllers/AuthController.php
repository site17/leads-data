<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Поиск пользователя по email
        $user = User::where('email', $request->email)->first();

        // Проверка существования пользователя и соответствия пароля
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Генерация токена
        $token = $user->createToken('api_token')->plainTextToken;

        // Возврат токена в ответе
        return response()->json(
            [
                'token' => $token

            ],
            200
        );
    }

    public function userInfo(Request $request)
    {

        // Получаем текущего авторизованного пользователя
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }
        $user = User::where('id', $user->id)->first();
        $user_project = userRight::where('user_pid', $user->id)->first();
     
        $user_data =  (object)[
            'name' => $user->name,
            'project' => $user_project->project_name,
        ];

        return response()->json([

            'data' => $user_data // возвращаем массив статусов
        ], 200);

    }
}
