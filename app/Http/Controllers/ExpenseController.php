<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller as BaseController;

// Clase para gestionar los gastos
class ExpenseController extends BaseController
{
    // Categorias de gastos permitidas
    private const CATEGORIES = [
        'comestibles', 'ocio', 'electronica', 'utilidades', 'ropa', 'salud', 'coleccionables', 'transporte', 'juegos', 'otros'
    ];
    // Middleware para autenticación con JWT
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Método para el endpoint GET /expenses que devuelve todos los gastos del usuario autenticado
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $expenses = Expense::where('user_id', $user->id)->get();
        return response()->json($expenses);
    }

    // Método para el endpoint POST /expenses que añade un gasto
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

    // Método para el endpoint GET /expenses/{id} que devuelve un gasto por su id
    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $expense = Expense::where('id', $id)->where('user_id', $user->id)->first();

        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return response()->json($expense);
    }

    // Método para el endpoint PUT /expenses/{id} que actualiza un gasto por su id
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

    // Método para el endpoint DELETE /expenses/{id} que elimina un gasto por su id
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

    // Método para el endpoint GET /expenses/category/{category} que devuelve los gastos de una categoría
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
