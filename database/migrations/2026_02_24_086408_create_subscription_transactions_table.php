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
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('name');
            $table->integer('number');
            $table->decimal('amount', 8, 2);
            $table->integer('duration_days');
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->dateTime('paid_date');
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'package_id']);
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
