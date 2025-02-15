<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

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

    public function test_can_get_expenses_by_category()
    {
        Expense::factory()->create(['user_id' => $this->user->id, 'category' => 'comestibles']);

        $response = $this->getJson('/api/expenses/category/comestibles', ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([['id', 'description', 'amount', 'date', 'category', 'user_id']]);
    }

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

    public function test_can_get_all_expenses()
    {
        Expense::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/expenses', ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'description', 'amount', 'date', 'category', 'user_id']
            ]);
    }

    public function test_can_delete_expense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson('/api/expenses/' . $expense->id, [], ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Gasto eliminado con éxito.']);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
