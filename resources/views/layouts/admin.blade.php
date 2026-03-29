<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }

        nav a {
            animation: slideIn 0.3s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans">
    @livewire('sidebar')
    <div class="flex flex-1 flex-col min-w-0 md:ml-50 lg:ml-64">
        <x-navbar.admin></x-navbar.admin>
        <main class="p-4 md:p-8 flex-1 min-w-0 fade-in-up ">
            {{ $slot }}
        </main>
        @livewire('pages::admin.crud-book.create')
        @livewire('pages::admin.crud-book.delete')
        @livewire('pages::admin.crud-book.update')
    </div>
    @livewireScripts
</body>

</html>
