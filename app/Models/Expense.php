<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Campos de los gastos a rellenar por el usuario
    protected $fillable = [
        'description',
        'amount',
        'date',
        'user_id',
        'category',
    ];

    // Relación con el modelo User, un gasto pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
