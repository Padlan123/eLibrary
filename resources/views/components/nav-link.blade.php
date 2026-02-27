<a href="{{ route($route) }}"
    {{ $attributes->merge([
        'class' =>
            'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold' .
            ($class ? ' ' . $class : '') .
            ($isActive() ? ' bg-blue-600 shadow' : ''),
    ]) }}>
    <ion-icon name="{{ $icon }}"></ion-icon>
    {{ $label }}
</a>
