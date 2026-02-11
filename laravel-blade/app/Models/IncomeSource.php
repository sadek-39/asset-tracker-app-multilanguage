<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeSource extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'source_name', 'source_type', 'current_balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomeTransactions()
    {
        return $this->hasMany(IncomeTransaction::class, 'source_id');
    }

    public function expenseTransactions()
    {
        return $this->hasMany(ExpenseTransaction::class, 'source_id');
    }
}
