<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Amo;



Route::get('/{any}', function () {
    return view('home'); // Имя вашего основного Vue шаблона
})->where('any', '.*');
// (namespace) — указывает на директорию, где находятся контроллеры. В нашем случае это App\Http\Controllers\Amo.
// (prefix) — добавляется ко всем маршрутам, указанным внутри группы. Это значит, что все маршруты внутри этой группы будут начинаться с /crm