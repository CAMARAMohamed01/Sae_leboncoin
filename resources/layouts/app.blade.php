<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    {{-- Menu de navigation (Exemple) --}}
    <nav class="bg-white shadow mb-8 p-4">
        <div class="container mx-auto">
            <a href="/" class="text-xl font-bold text-blue-600">MonSite</a>
        </div>
    </nav>

    {{-- C'est ICI que le contenu de inscription_perso sera injecté --}}
    <main>
        @yield('content')
    </main>

</body>
</html>