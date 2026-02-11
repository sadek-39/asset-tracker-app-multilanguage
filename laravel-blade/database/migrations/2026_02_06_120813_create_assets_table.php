<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('asset_type', ['dps', 'bond', 'investment', 'other']);
            $table->string('asset_name', 100);
            $table->decimal('current_value', 15, 2);
            $table->string('institution_name', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
