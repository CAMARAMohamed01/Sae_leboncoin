<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leboncoin - Ventes immobilières et touristiques</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lbc: {
                            orange: '#ec5a13', // L'orange officiel
                            orange_hover: '#d64d0e',
                            blue: '#366dc3', // Le bleu des liens
                            bg: '#f4f6f7', // Le gris clair de fond
                        }
                    },
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Petite retouche pour que le footer reste en bas si la page est vide */
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }
    </style>
</head>
<body class="bg-lbc-bg text-gray-800 font-sans">

    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="container mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-3xl font-extrabold text-lbc-orange tracking-tighter">
                    le<span class="text-gray-800">bon</span>coin
                </a>

                <a href="#" class="hidden md:flex items-center gap-2 bg-lbc-orange hover:bg-lbc-orange_hover text-white px-4 py-2 rounded-lg font-bold transition shadow-sm text-sm">
                    <i class="fa-solid fa-plus-square text-lg"></i>
                    Déposer une annonce
                </a>
            </div>

            <nav class="flex items-center gap-6 text-xs md:text-sm font-medium text-gray-600">
                
                <a href="{{ route('recherche.index') }}" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-solid fa-magnifying-glass text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Mes Recherches</span>
                </a>

                <a href="#" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-regular fa-heart text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Favoris</span>
                </a>

                <a href="#" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-regular fa-comment-dots text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Messages</span>
                </a>

                <a href="#" class="flex flex-col items-center hover:text-lbc-orange transition gap-1 ml-2 border-l pl-6 border-gray-200">
                    <i class="fa-regular fa-user text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Se connecter</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="py-8">
        @if(session('status'))
            <div class="container mx-auto px-4 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12 pt-8 pb-6 text-sm">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Leboncoin</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:underline">Qui sommes-nous ?</a></li>
                    <li><a href="#" class="hover:underline">Nous rejoindre</a></li>
                    <li><a href="#" class="hover:underline">Impact environnemental</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Nos services</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:underline">Informations légales</a></li>
                    <li><a href="#" class="hover:underline">Publicité</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Aide</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:underline">Centre d'aide</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="flex gap-4 mt-2">
                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center"><i class="fa-brands fa-facebook-f text-gray-600"></i></div>
                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center"><i class="fa-brands fa-twitter text-gray-600"></i></div>
                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center"><i class="fa-brands fa-instagram text-gray-600"></i></div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 mt-8 pt-6 text-center text-gray-400 text-xs">
            <p>Leboncoin 2024 - 2025 • Projet Universitaire • Reproduit à des fins pédagogiques</p>
        </div>
    </footer>

</body>
</html>