<?php

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = User::firstOrCreate([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123')
        ]);

        $user->assignRole('admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'admin@gmail.com')->delete();
    }
};
