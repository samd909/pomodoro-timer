<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Pomodoro timer')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
        .flex-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .flex-grow {
            flex: 1;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex-container">
        
        <header class="bg-transparent text-black p-4 shadow">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-black text-2xl font-bold">Pomodoro timer</h1>
            </div>
        </header>

        <main class="container mx-auto py-8 flex-grow">
            @yield('content')
        </main>

        <footer class="bg-transparent text-black p-4 text-center">
            <p>&copy; {{ date('Y') }} Sam Droste.</p>
        </footer>

    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
