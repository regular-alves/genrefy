<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authorization;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/register/authorization', [Authorization::class, 'register']);
Route::post('/return', [Authorization::class, 'return']);
