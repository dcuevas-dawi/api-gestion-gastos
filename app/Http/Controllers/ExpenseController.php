<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller as BaseController;

class ExpenseController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $expenses = Expense::all();
        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = Expense::create($validatedData);
        return response()->json(['message' => 'Gasto añadido con éxito.'], 201);
    }

    public function show($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $validatedData = $request->validate([
            'description' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'date' => 'sometimes|required|date',
        ]);

        $expense->update($validatedData);
        return response()->json(['message' => 'Gasto actualizado con éxito.']);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $expense->delete();
        return response()->json(['message' => 'Gasto eliminado con éxito.']);
    }
}
