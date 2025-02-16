<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\User;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // Clase para rellenar la tabla de gastos con datos de muestra
    public function run(): void
    {
        $user = User::first();

        $expenses = [
            [
                'description' => 'Compra de oficina',
                'amount' => 150.75,
                'date' => '2023-10-16',
                'category' => 'comestibles',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Cena con amigos',
                'amount' => 75.50,
                'date' => '2023-10-17',
                'category' => 'ocio',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Compra de oficina',
                'amount' => 150.75,
                'date' => '2023-10-16',
                'category' => 'comestibles',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Cena con amigos',
                'amount' => 75.50,
                'date' => '2023-10-17',
                'category' => 'ocio',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Compra de laptop',
                'amount' => 1200.00,
                'date' => '2023-10-18',
                'category' => 'electronica',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Pago de electricidad',
                'amount' => 60.00,
                'date' => '2023-10-19',
                'category' => 'utilidades',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Compra de ropa',
                'amount' => 200.00,
                'date' => '2023-10-20',
                'category' => 'ropa',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Consulta médica',
                'amount' => 100.00,
                'date' => '2023-10-21',
                'category' => 'salud',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Compra de cómics',
                'amount' => 50.00,
                'date' => '2023-10-22',
                'category' => 'coleccionables',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Pago de transporte',
                'amount' => 30.00,
                'date' => '2023-10-23',
                'category' => 'transporte',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Compra de videojuegos',
                'amount' => 80.00,
                'date' => '2023-10-24',
                'category' => 'juegos',
                'user_id' => $user->id,
            ],
            [
                'description' => 'Gasto misceláneo',
                'amount' => 25.00,
                'date' => '2023-10-25',
                'category' => 'otros',
                'user_id' => $user->id,
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}
