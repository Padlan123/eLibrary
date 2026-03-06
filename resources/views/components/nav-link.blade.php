<a href="{{ route($route) }}"
    {{ $attributes->merge([
        'class' =>
            'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold ' .
            'transition-all duration-300 ease-in-out ' .
            ($class ? ' ' . $class : '') .
            ($isActive()
                ? ' bg-blue-600 shadow scale-[1.02] opacity-100'
                : ' opacity-70 hover:opacity-100 hover:bg-white/10 hover:scale-[1.01]'),
    ]) }}>
    {{ $slot }}
    {{ $label }}
</a>
