<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->enum('type', ['expense', 'income', 'debt']);
            $table->decimal('amount', 15, 2);
            $table->decimal('base_amount_uzs', 15, 2);
            $table->string('category')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'paid' - for debts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
