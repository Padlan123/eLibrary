<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'anggota']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::where(['name', 'admin'])->delete();
        Role::where(['name', 'anggota'])->delete();
    }
};
