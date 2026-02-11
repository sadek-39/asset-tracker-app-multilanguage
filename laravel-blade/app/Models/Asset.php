<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'asset_type', 'asset_name', 'current_value', 'institution_name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
