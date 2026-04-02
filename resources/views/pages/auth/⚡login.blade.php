<?php
use App\Traits\WithAuthValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use WithAuthValidation;

    public string $email = '';
    public string $password = '';

    public function mount()
    {
        if (auth()->check()) {
            $role = auth()->user()->getRoleNames()->first();
            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'anggota' => redirect()->route('anggota.home'),
                default => redirect()->route('login'),
            };
        }
    }

    public function login()
    {
        $validated = $this->validateLogin();

        if (!Auth::attempt($validated)) {
            $this->addError('email', 'email atau password anda salah');
            return;
        }
        $role = Auth::user()->getRoleNames()->first();
        return $this->redirectTo($role);
    }

    public function redirectTo($role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'anggota' => redirect()->route('anggota.home'),
            default => Auth::logout() && redirect()->route('login'),
        };
    }

    public function render()
    {
        return $this->view()->title('Login');
    }
};
?>
<div>
    <div
        class="bg-linear-to-br from-orange-100 via-blue-400 to-blue-600 flex items-center justify-center font-sans fade-in-up">
        <div class="min-h-screen w-full flex items-center justify-center px-4">
            <div class="bg-white/30 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-orange-50 bg-clip-text mb-2">
                        READIFY
                    </h1>
                    <p class="text-gray-600">Masuk ke akun Anda</p>
                </div>

                <form wire:submit="login" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input wire:model="email" type="text" id="email"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan email Anda" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-500"><span class="font-medium">{{ $message }}</span></p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input wire:model="password" type="password" id="password"
                            class="w-full bg-orange-50 py-3 px-4 border-2 rounded-lg border-blue-500 focus:border-blue-300 focus:ring-1 focus:ring-blue-200 focus:outline-none transition duration-300"
                            placeholder="Masukkan password Anda" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-500"><span class="font-medium">{{ $message }}</span></p>
                        @enderror
                    </div>

                    <button wire:loading.class="opacity-50" type="submit"
                        class="w-full py-3 px-4 bg-linear-to-r from-blue-500 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition duration-300">
                        <div wire:loading.remove>
                            Masuk
                        </div>
                        <div wire:loading>
                            <p>loading......</p>
                        </div>
                    </button>
                </form>
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a wire:navigate href="{{ route('register') }}"
                            class="text-blue-600 hover:text-blue-500 font-medium">Daftar
                            sekarang</a>
                    </p>
                </div>
            </div>
            @if (session('sukses'))
                <div x-data="{ open: false }" x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-800"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-10" x-init="setTimeout(() => {
                        open = true;
                        setTimeout(() => open = false, 3000)
                    }, 700)"
                    class="fixed top-12 z-50 flex items-center w-full max-w-sm px-4 py-2 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default"
                    role="alert">
                    <div
                        class="inline-flex items-center justify-center shrink-0 w-7 h-7 text-fg-success bg-success-soft rounded">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 11.917 9.724 16.5 19 7.5" />
                        </svg>
                        <span class="sr-only">Check icon</span>
                    </div>
                    <div class="ms-3 text-sm font-normal">{{ session('sukses') }}</div>
                    <button @click="open = false" type="button"
                        class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none"
                        aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18 17.94 6M18 18 6.06 6" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>

    </div>

    <x-footer></x-footer>
</div>
