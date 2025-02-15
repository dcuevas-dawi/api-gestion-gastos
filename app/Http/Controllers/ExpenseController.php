<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller as BaseController;

class ExpenseController extends BaseController
{
    private const CATEGORIES = [
        'comestibles', 'ocio', 'electronica', 'utilidades', 'ropa', 'salud', 'coleccionables', 'transporte', 'juegos', 'otros'
    ];
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $expenses = Expense::where('user_id', $user->id)->get();
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $validatedData = $request->validate([
                'description' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'category' => 'required|string|in:' . implode(',', self::CATEGORIES),
            ]);

            $expense = new Expense($validatedData);
            $expense->user_id = $user->id;
            $expense->save();
            return response()->json(['message' => 'Gasto añadido con éxito.'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $expense = Expense::where('id', $id)->where('user_id', $user->id)->first();

        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $expense = Expense::where('id', $id)->where('user_id', $user->id)->first();

            if (!$expense) {
                return response()->json(['error' => 'Expense not found'], 404);
            }

            $validatedData = $request->validate([
                'description' => 'sometimes|required|string|max:255',
                'amount' => 'sometimes|required|numeric',
                'date' => 'sometimes|required|date',
                'category' => 'sometimes|required|string|in:' . implode(',', self::CATEGORIES),
            ]);

            $expense->fill($validatedData);
            $expense->save();

            return response()->json(['message' => 'Gasto actualizado con éxito.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $expense = Expense::where('id', $id)->where('user_id', $user->id)->first();

        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $expense->delete();
        return response()->json(['message' => 'Gasto eliminado con éxito.']);
    }

    public function getByCategory($category)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!in_array($category, self::CATEGORIES)) {
            return response()->json(['error' => 'Categoria incorrecta'], 422);
        }

        $expenses = Expense::where('user_id', $user->id)->where('category', $category)->get();

        return response()->json($expenses);
    }
}
