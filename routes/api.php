<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::get('expenses/{id}', [ExpenseController::class, 'show']);
    Route::put('expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('expenses/{id}', [ExpenseController::class, 'destroy']);
    Route::get('expenses/category/{category}', [ExpenseController::class, 'getByCategory']);
});
