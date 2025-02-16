<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Tymon\JWTAuth\Facades\JWTAuth;

// Tests para el CRUD de gastos
class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    // Crear un usuario y obtener el token de autenticación
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    // Test para testear la creación de un gasto
    public function test_can_create_expense()
    {
        $response = $this->postJson('/api/expenses', [
            'description' => 'Test Expense',
            'amount' => 100,
            'date' => '2023-10-16',
            'category' => 'comestibles',
        ], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(201);
    }

    // Test para testear la actualización de un gasto
    public function test_can_update_expense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson('/api/expenses/' . $expense->id, [
            'description' => 'Updated Expense',
            'amount' => 150,
        ], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Gasto actualizado con éxito.']);
    }

    // Test para testear la obtención de gastos por categoría
    public function test_can_get_expenses_by_category()
    {
        Expense::factory()->create(['user_id' => $this->user->id, 'category' => 'comestibles']);

        $response = $this->getJson('/api/expenses/category/comestibles', ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([['id', 'description', 'amount', 'date', 'category', 'user_id']]);
    }

    // Test para testear insertar una categoría inválida
    public function test_invalid_category_returns_error()
    {
        $response = $this->postJson('/api/expenses', [
            'description' => 'Test Expense',
            'amount' => 100,
            'date' => '2023-10-16',
            'category' => 'invalid_category',
        ], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(422)
            ->assertJson(['error' => ['category' => ['The selected category is invalid.']]]);
    }

    // Test para testear la obtención de todos los gastos
    public function test_can_get_all_expenses()
    {
        Expense::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/expenses', ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'description', 'amount', 'date', 'category', 'user_id']
            ]);
    }

    // Test para testear la eliminación de un gasto
    public function test_can_delete_expense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson('/api/expenses/' . $expense->id, [], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Gasto eliminado con éxito.']);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    // Test para testear la obtención de un gasto por su id
    public function test_can_get_expense_by_id()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id, 'category' => 'otros']);

        $response = $this->getJson('/api/expenses/' . $expense->id, ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $expense->id,
                'description' => $expense->description,
                'amount' => $expense->amount,
                'date' => $expense->date,
                'category' => 'otros',
                'user_id' => $expense->user_id,
            ])
            ->assertJsonStructure([
                'id',
                'description',
                'amount',
                'date',
                'category',
                'user_id',
                'created_at',
                'updated_at',
            ]);
    }
}
