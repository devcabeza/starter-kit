<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Laravertex</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/favicon" href="{{ asset('favicon.svg') }}">

        <!-- styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class='antialiased font-sans'>
        <main class='flex justify-center items-center h-screen w-screen flex-col gap-6'>
            <img src="{{asset('favicon.svg')}}" class='w-[5rem] h-[5rem]' />
            <h1 class='text-6xl font-bold'>Laravertex</h1>
            <span>Bienvenido, crea y prueba rapido, distribuye y gana de inmediato</span>

            <a href="https://github.com/devcabeza/starter-kit/releases/latest/download/app-debug.apk"
               download="Laravertex.apk"
               class="mt-4 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-all duration-200 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Descargar APK
            </a>
        </main>
    </body>
</html>
