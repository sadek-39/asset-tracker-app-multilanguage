<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'source_id', 'amount', 'description', 'transaction_date'];

    public function source()
    {
        return $this->belongsTo(IncomeSource::class, 'source_id');
    }
}
