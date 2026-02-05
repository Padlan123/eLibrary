<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected $rules = [
        'username' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];

    public function register()
    {
        $validated = $this->validate();

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('anggota');

        return $this->redirect('/login');
    }

    public function render()
    {
        return $this->view()->title('register');
    }
};
?>
<div>
    <div
        class="bg-linear-to-br from-orange-100 via-blue-400 to-blue-600 flex items-center justify-center font-sans sm:py-32 lg:py-8">
        <div class="min-h-screen w-full flex items-center justify-center px-4">
            <div class="bg-white/30 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-orange-50 bg-clip-text mb-2">
                        KOMIKPAGE
                    </h1>
                    <p class="text-gray-600">Daftar sekarang</p>
                </div>
                <form wire:submit="register" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input wire:model="username" type="text" id="username"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan username Anda" />
                        @error('username')
                            {{ $message }}
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input wire:model="email" type="email" id="email"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan email Anda" />
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input wire:model="password" type="password" id="password"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan password Anda" />
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmed" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                            Password</label>
                        <input wire:model="password_confirmation" type="password" id="password_confirmed"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan ulang password Anda" />
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-linear-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition duration-300">
                        Daftar
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <a wire:navigate href="{{ route('login') }}"
                            class="text-blue-600 hover:text-blue-500 font-medium">Log
                            in
                            sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
