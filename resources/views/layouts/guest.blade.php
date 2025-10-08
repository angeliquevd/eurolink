<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Communication hub for the European Commission and stakeholders</title>
        <meta name="description" content="Eurolink centralises spaces, threads, and announcements—so projects move faster and stay compliant. Built with EU-grade security.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Flux Appearance -->
        @fluxAppearance

        <!-- Flux CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/livewire/flux/dist/flux.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-white dark:bg-gray-900">
        <div class="font-sans antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
        @fluxScripts
    </body>
</html>
