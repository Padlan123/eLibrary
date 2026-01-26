<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>
    {{ $slot }}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({
                    behavior: 'smooth'
                });

                // Hilangkan hash dari URL
                history.replaceState(null, null, ' ');
            });
        });
    </script>
    @livewireScripts
</body>

</html>
