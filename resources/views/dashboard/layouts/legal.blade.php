<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }} - {{ config('app.name', 'AYS Developer APP') }}</title>

        <link rel="icon" href="{{ asset('assets/dashboard/images/favicon.png') }}" type="image/x-icon">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col items-center py-10 px-4">
            <div class="mb-6">
                <a href="/login">
                    <img src="{{ asset('assets/dashboard/images/logo.webp') }}" alt="logo" class="img-fluid w-32">
                </a>
            </div>

            <div class="w-full max-w-3xl bg-white shadow-md rounded-lg px-6 py-8 sm:px-10 sm:py-10">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $title }}</h1>
                <p class="text-sm text-gray-500 mb-8">Last updated: {{ $lastUpdated }}</p>

                <div class="space-y-6 text-gray-700 leading-relaxed [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:text-gray-900 [&_h2]:mt-8 [&_h2]:mb-2 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-1 [&_a]:text-primary [&_a]:underline">
                    {{ $slot }}
                </div>
            </div>

            <a href="/login" class="mt-6 text-sm text-gray-600 hover:text-gray-900 underline">&larr; Back to login</a>
        </div>
    </body>
</html>
