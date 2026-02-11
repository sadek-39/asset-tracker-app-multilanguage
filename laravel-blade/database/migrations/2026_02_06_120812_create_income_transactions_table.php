<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_id')->constrained('income_sources')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
            $table->index('user_id');
            $table->index('transaction_date');
            $table->index(['user_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('income_transactions');
    }
};
