<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bingo - Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-blue-700 text-white p-4 shadow">
        <h1 class="text-xl font-bold">Bingo - Plataforma de Pruebas</h1>
    </header>

    <main class="p-6">
        @yield('content')
    </main>

    <footer class="text-center text-sm text-gray-500 py-4">
        Sistema de Bingo - Mario © {{ date('Y') }}
    </footer>

</body>
</html>
