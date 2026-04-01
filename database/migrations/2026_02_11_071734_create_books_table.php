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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->integer('publication_year');
            $table->enum('language', ['Indonesia', 'English']);
            $table->text('summary')->nullable();
            $table->boolean('subscription')->default(false);
            $table->integer('total_pages');
            $table->boolean('is_recommended')->default(false);
            $table->string('cover_file_name');
            $table->string('pdf_file_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
