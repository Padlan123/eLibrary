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
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('name');
            $table->string('number');
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->dateTime('paid_date');
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};
