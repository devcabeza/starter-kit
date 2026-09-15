<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>{{config('app.name')}}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/favicon" href="{{ asset('favicon.svg') }}">

        <!-- styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class='antialiased font-sans'>
        <main class='flex justify-center items-center h-screen w-screen flex-col'>
            <img src="{{asset('favicon.svg')}}" class='w-[5rem] h-[5rem]' />
            <h1 class='text-6xl font-bold'>{{config('app.name')}}</h1>
            <span>Bienvenido, crea y prueba rapido, distribuye y gana de inmediato</span>
        </main>
    </body>
</html>


