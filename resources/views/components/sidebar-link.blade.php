@props(['href', 'active' => false])

@php
$classes = $active
            ? 'block items-center px-4 py-2 border-b-2 border-primary dark:border-primary text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-primary transition duration-150 ease-in-out'
            : 'block items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none transition duration-150 ease-in-out';
@endphp
<div   {{ $attributes->merge(['class' => $classes]) }}>
    <a href="{{ $href }}">
        {{ $slot }}
    </a>
</div>