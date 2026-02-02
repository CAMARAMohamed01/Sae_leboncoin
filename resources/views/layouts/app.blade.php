<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leboncoin - Ventes immobilières et touristiques</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lbc: {
                            orange: '#ec5a13',
                            orange_hover: '#d64d0e',
                            blue: '#366dc3',
                            bg: '#f4f6f7',
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
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; }

        #botmanWidgetRoot div {
            --botman-widget-color: #ec5a13 !important;
        }

        #botmanWidgetRoot div div { 
            color:white !important; 
        }

        .desktop-closed-message-avatar img {
            border: 2px solid #ec5a13 !important;
        }
        
        .btn-tuto { cursor: pointer; }
    </style>
</head>
<body class="bg-lbc-bg text-gray-800 font-sans">

    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="container mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" id="logo-home" class="text-3xl font-extrabold text-lbc-orange tracking-tighter">
                    le<span class="text-gray-800">bon</span>coin
                </a>
                
                <a href="http://51.83.36.122:8012/annonces/creer" id="btn-deposer-annonce" class="hidden md:flex items-center gap-2 bg-lbc-orange hover:bg-lbc-orange_hover text-white px-4 py-2 rounded-lg font-bold transition shadow-sm text-sm">
                    <i class="fa-solid fa-plus-square text-lg"></i>
                    Déposer une annonce
                </a>
            </div>

            <nav class="flex items-center gap-6 text-xs md:text-sm font-medium text-gray-600">
                
                <a href="{{ route('hoistorique.index') }}" id="nav-recherche" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-solid fa-magnifying-glass text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Mes Recherches</span>
                </a>

                <a href="{{ route('annonces.mes_favoris') }}" id="nav-favoris" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-regular fa-heart text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Favoris</span>
                </a>

                <button onclick="lancerTutoriel()" class="btn-tuto flex flex-col items-center text-lbc-blue hover:text-lbc-orange transition gap-1">
                    <i class="fa-solid fa-circle-question text-lg mb-0.5"></i>
                    <span class="hidden md:inline">Aide</span>
                </button>

                @auth
                <div class="ml-2 border-l pl-6 border-gray-200">
                    <div class="flex flex-col items-center justify-center">
                        <a href="/profil" id="nav-profil" class="flex items-center gap-2 mb-0.5 group">
                            <i class="fa-solid fa-user text-lbc-orange"></i>
                            <span class="font-bold text-gray-800 text-xs truncate max-w-[150px] group-hover:underline decoration-lbc-orange">
                                {{ Auth::user()->emailutilisateur }}
                            </span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

                @guest
                <a href="{{ route('login') }}" id="nav-connexion" class="flex flex-col items-center hover:text-lbc-orange transition gap-1">
                    <i class="fa-regular fa-user text-lg mb-0.5"></i>
                    <span>Se connecter</span>
                </a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="py-8">
        @yield('content')
    </main>

    
    <footer class="bg-white border-t border-gray-200 mt-12 pt-8 pb-6 text-sm">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Leboncoin</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:underline">Qui sommes-nous ?</a></li>
                    <li><a href="#" class="hover:underline">Nous rejoindre</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Nos services</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="{{route('legal.privacy')}}" id="footer-legal" class="hover:underline">Informations légales</a></li>
                    <li><a href="#" class="hover:underline">Publicité</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-4">Aide</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="{{ route('aide.index') }}" id="footer-faq" class="hover:underline">FAQ (questions)</a></li>
                    <li><a href="{{ route('contact.index') }}" id="footer-contact" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <div class="flex gap-4 mt-2">
                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center"><i class="fa-brands fa-facebook-f text-gray-600"></i></div>
                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 mt-8 pt-6 text-center text-gray-400 text-xs">
            <p>Leboncoin 2025 • Projet Universitaire</p>
        </div>
    </footer>

    1.	<script>
    var botmanWidget = {
        chatServer: '/botman',
        aboutText: 'Assistant Leboncoin',
        introMessage: "Hey, je peux t'aider ?",
        title: "Support LeBoncoin",
        mainColor: "#ec5a13",
        bubbleBackground: "#ec5a13", 
        headerTextColor: "#ffffff",
    };
</script>

<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>



    @include('partiels.cookies')

    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <script>
    function lancerTutoriel() {
        const driver = window.driver.js.driver;
        let steps = [];

        const isAnnoncePage = document.getElementById('gallery-trigger');
        const isHomePage = document.getElementById('home-search-bar');
        const isSearchPage = document.getElementById('search-filters');

        if (isHomePage) {
            steps.push({
                element: '#logo-home',
                popover: { title: 'Accueil', description: 'Cliquez ici pour revenir à la page principale à tout moment.', side: 'bottom', align: 'start' }
            });
            steps.push({
                element: '#home-search-bar',
                popover: { title: 'Recherche', description: 'Commencez votre recherche ici (ville, catégorie). Validez pour accéder à plus de filtres et affiner vos résultats.', side: 'bottom', align: 'center' }
            });
            steps.push({
                element: '#btn-deposer-annonce',
                popover: { title: 'Déposer une annonce', description: 'Cliquez ici pour mettre un bien en vente ou en location (connexion requise).', side: 'bottom', align: 'center' }
            });
            
            if(document.getElementById('nav-profil')) {
                steps.push({ element: '#nav-profil', popover: { title: 'Mon Compte', description: 'Accédez à votre espace personnel.', side: 'left' } });
            } else if (document.getElementById('nav-connexion')) {
                steps.push({ element: '#nav-connexion', popover: { title: 'Connexion', description: 'Connectez-vous pour accéder à toutes les fonctionnalités.', side: 'left' } });
            }

            steps.push(
                { element: '#nav-recherche', popover: { title: 'Historique', description: 'Retrouvez ici vos dernières recherches.', side: 'bottom' } },
                { element: '#nav-favoris', popover: { title: 'Favoris', description: 'Accédez à vos annonces sauvegardées.', side: 'bottom' } }
            );
            steps.push(
                { element: '#footer-faq', popover: { title: 'FAQ', description: 'Consultez les réponses aux questions fréquentes.', side: 'top' } },
                { element: '#footer-contact', popover: { title: 'Contact', description: 'Contactez le support technique.', side: 'top' } }
            );
        }

        else if (isAnnoncePage) {
            steps.push(
                { element: '#gallery-trigger', popover: { title: 'Photos', description: 'Cliquez pour afficher la galerie en grand format.', side: 'bottom' } },
                { element: '#annonce-header', popover: { title: 'Informations', description: 'Titre, prix et date de publication.', side: 'bottom' } },
                { element: '#booking-action', popover: { title: 'Action', description: 'Réserver ce bien ou contacter le vendeur.', side: 'left' } },
                { element: '#annonce-specs', popover: { title: 'Caractéristiques', description: 'Détails techniques et équipements.', side: 'right' } },
                { element: '#annonce-map', popover: { title: 'Localisation', description: 'Emplacement du bien sur la carte.', side: 'top' } },
                { element: '#sidebar-owner', popover: { title: 'Vendeur', description: 'Profil du propriétaire et avis.', side: 'left' } }
            );
            
            steps.push({ element: '#logo-home', popover: { title: 'Accueil', description: 'Retour à la page d\'accueil.', side: 'bottom' } });
            steps.push(
                { element: '#nav-favoris', popover: { title: 'Favoris', description: 'Sauvegarder cette annonce.', side: 'bottom' } },
                { element: '#nav-recherche', popover: { title: 'Historique', description: 'Vos recherches récentes.', side: 'bottom' } }
            );

            if(document.getElementById('nav-profil')) {
                steps.push({ element: '#nav-profil', popover: { title: 'Compte', description: 'Votre espace personnel.', side: 'bottom' } });
            } else if (document.getElementById('nav-connexion')) {
                steps.push({ element: '#nav-connexion', popover: { title: 'Connexion', description: 'Se connecter.', side: 'bottom' } });
            }

            steps.push(
                { element: '#footer-faq', popover: { title: 'Aide', description: 'Consulter la FAQ.', side: 'top' } },
                { element: '#footer-contact', popover: { title: 'Contact', description: 'Contacter le support.', side: 'top' } }
            );
        }

        else if (isSearchPage) {
            // Filtres
            steps.push({
                element: '#search-filters',
                popover: { title: 'Filtres', description: 'Définissez votre destination, vos dates et le type de logement.', side: 'bottom' }
            });
            steps.push({
                element: '#toggle-advanced-search',
                popover: { title: 'Plus d\'options', description: 'Affinez la recherche (budget, équipements, etc.).', side: 'bottom' }
            });
            steps.push({
                element: '#btn-save-search',
                popover: { title: 'Sauvegarder', description: 'Enregistrez cette recherche pour plus tard.', side: 'bottom' }
            });

            // Résultats
            steps.push({
                element: '#results-container',
                popover: { title: 'Résultats', description: 'Liste des annonces correspondantes.', side: 'right' }
            });
            steps.push({
                element: '#map-container',
                popover: { title: 'Carte', description: 'Vue géographique des résultats.', side: 'left' }
            });

            steps.push({
                element: '#logo-home',
                popover: { title: 'Accueil', description: 'Retour à la page d\'accueil.', side: 'bottom' }
            });
            steps.push({
                element: '#btn-deposer-annonce',
                popover: { title: 'Vendre', description: 'Déposer une annonce (connexion requise).', side: 'bottom' }
            });

            if(document.getElementById('nav-profil')) {
                steps.push({ element: '#nav-profil', popover: { title: 'Compte', description: 'Gérer votre profil.', side: 'left' } });
            } else if (document.getElementById('nav-connexion')) {
                steps.push({ element: '#nav-connexion', popover: { title: 'Connexion', description: 'Se connecter.', side: 'left' } });
            }

            steps.push(
                { element: '#footer-faq', popover: { title: 'FAQ', description: 'Questions fréquentes.', side: 'top' } },
                { element: '#footer-contact', popover: { title: 'Contact', description: 'Support client.', side: 'top' } }
            );
        }

        else {
            steps.push({ element: '#logo-home', popover: { title: 'Accueil', description: 'Retour à l\'accueil.', side: 'bottom' } });
            
            if (document.getElementById('profil-sidebar')) {
                steps.push({ element: '#profil-sidebar', popover: { title: 'Menu', description: 'Menu de gestion du compte.', side: 'right' } });
            }

            steps.push({
                element: '#btn-deposer-annonce',
                popover: { title: 'Vendre', description: 'Déposer une annonce.', side: 'bottom' }
            });

            if(document.getElementById('nav-profil')) {
                steps.push({ element: '#nav-profil', popover: { title: 'Compte', description: 'Votre espace.', side: 'left' } });
            } else if (document.getElementById('nav-connexion')) {
                steps.push({ element: '#nav-connexion', popover: { title: 'Connexion', description: 'Se connecter.', side: 'left' } });
            }

            steps.push(
                { element: '#footer-faq', popover: { title: 'Aide', description: 'Foire Aux Questions.', side: 'top' } },
                { element: '#footer-contact', popover: { title: 'Contact', description: 'Service client.', side: 'top' } }
            );
        }

        const driveObj = driver({
            showProgress: true,
            animate: true,
            steps: steps,
            nextBtnText: 'Suivant',
            prevBtnText: 'Précédent',
            doneBtnText: 'Terminer',
            progressText: 'Étape @{{current}} sur @{{total}}',
            allowClose: true,
            overlayClickNext: false
        });

        driveObj.drive();
    }
</script>

</body>
</html>