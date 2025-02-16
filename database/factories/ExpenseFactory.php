<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

// Definir la factoría para el modelo Expense. Esto se encarga de generar gastos falsos para pruebas
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'date' => $this->faker->date,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
