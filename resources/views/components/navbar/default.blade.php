<div>
    <header class="fixed top-0 w-full shadow-md bg-linear-to-r from-blue-500 via-blue-400 to-blue-400 z-50">
        <nav class="max-w-7xl mx-auto px-8 py-4 flex items-center justify-between">
            <div>
                <a href="{{ route('welcome') }}">
                    <h1 class="text-xl font-bold text-white">Readify</h1>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-gray-200">Masuk</a>
                <a href="{{ route('register') }}"
                    class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm font-medium hover:bg-gray-100">Daftar</a>
            </div>
        </nav>
    </header>
</div>
