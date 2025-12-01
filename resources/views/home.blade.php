@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 mt-6">
    <div class="bg-lbc-orange/10 rounded-2xl p-8 md:p-12 text-center relative">
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">
            Des millions de petites annonces et autant d’occasions de se faire plaisir.
        </h1>

        <div class="bg-white p-4 rounded-xl shadow-lg max-w-4xl mx-auto relative z-10">
            <form action="{{ route('recherche.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center">
                
                <!-- ZONE DE LOCALISATION AVEC AUTOCOMPLÉTION -->
                <div class="flex-1 w-full relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-location-dot text-gray-400 group-focus-within:text-lbc-blue"></i>
                    </div>
                    
                    <input type="text" 
                           id="localisation-input" 
                           name="localisation" 
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange sm:text-sm transition" 
                           placeholder="Où cherchez-vous ? (Ville)">

                    <!-- Conteneur des résultats d'autocomplétion (nécessaire pour le JS) -->
                    <div id="autocomplete-results" 
                         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto hidden">
                        <!-- Les résultats seront insérés ici par JavaScript -->
                    </div>
                </div>
                <!-- FIN ZONE DE LOCALISATION -->

                <div class="hidden md:block w-px h-10 bg-gray-200"></div>
                
                <div class="flex-1 w-full relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-cube text-gray-400"></i>
                    </div>
                    <select name="type_hebergement" class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange sm:text-sm appearance-none transition text-gray-600">
                        <option value="">Toutes les catégories</option>
                        @foreach($typesHebergement as $type)
                            <option value="{{ $type->idtypehebergement }}">{{ $type->typehebergement }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>

                <button type="submit" class="w-full md:w-auto bg-lbc-blue hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-150 ease-in-out shadow-md">
                    Rechercher
                </button>
            </form>
        </div>

        <div class="absolute top-0 left-0 -ml-20 -mt-20 w-64 h-64 rounded-full bg-lbc-orange/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-64 h-64 rounded-full bg-blue-400/20 blur-3xl"></div>
    </div>
</div>

<!-- <div class="container mx-auto px-4 mt-12">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Top catégories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($typesHebergement as $type)
        <a href="{{ route('recherche.index', ['type_hebergement' => $type->idtypehebergement]) }}" 
           class="flex flex-col items-center justify-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md hover:bg-orange-50 transition cursor-pointer border border-transparent hover:border-lbc-orange/30 group">
            
            <div class="w-12 h-12 rounded-full bg-orange-100 text-lbc-orange flex items-center justify-center mb-3 group-hover:bg-lbc-orange group-hover:text-white transition">
                <i class="fa-solid fa-home text-xl"></i> 
            </div>
            
            <span class="text-sm font-semibold text-gray-700 text-center group-hover:text-lbc-orange">{{ $type->typehebergement }}</span>
        </a>
        @endforeach
    </div>
</div> -->

<div class="container mx-auto px-4 mt-16 mb-12">
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-xl font-bold text-gray-900">Dernières annonces publiées</h2>
        <a href="{{ route('recherche.index') }}" class="text-lbc-blue font-semibold hover:underline text-sm">Voir tout</a>
             
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($dernieresAnnonces as $annonce)
            <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden">
                <div class="relative h-48 bg-gray-200 overflow-hidden">
                    @if($annonce->photos->isNotEmpty())
                        <img src="{{ $annonce->photos->first()->lienurl }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                            <i class="fa-regular fa-image text-3xl"></i>
                        </div>
                    @endif
                    
                    @if($annonce->photos->count() > 1)
                    <div class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded-md">
                        <i class="fa-solid fa-camera mr-1"></i> {{ $annonce->photos->count() }}
                    </div>
                    @endif
                </div>

                <div class="p-4">
                    
                    <h3 class="font-bold text-gray-900 truncate group-hover:text-lbc-blue transition">{{ $annonce->titreannonce }}</h3>
    
                        @if(isset($annonce->tarifs_min_prixjour))
                            <p class="text-lbc-orange font-bold mt-1">
                                {{ $annonce->tarifs_min_prixjour }} € 
                                <span class="text-xs text-gray-500 font-normal">/nuit</span>
                            </p>
                        @else
                            <p class="text-gray-400 text-sm italic mt-1">Prix non renseigné</p>
                        @endif

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $annonce->ville->nomville ?? 'Localisation' }}
                        </span>
                        <span>
                                {{ $annonce->dateEnregistrement->dateacte ?? 'Date inconnue' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <!-- GESTION DE L'EXCEPTION : Affiché quand $dernieresAnnonces est vide -->
            <div class="col-span-4 text-center py-10 text-gray-500 border border-dashed rounded-lg border-gray-300 bg-gray-50 w-full">
                <p class="text-lg font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-triangle-exclamation mr-2 text-lbc-orange"></i>
                    Aucune annonce trouvée.
                </p>
                <p>Il n'y a actuellement aucune annonce correspondant à cette recherche ou à cette ville.</p>
                <p class="text-sm mt-3">Soyez le premier à déposer une annonce !</p>
            </div>
        @endforelse
    </div>
</div>

<!-- SECTION JAVASCRIPT PUR POUR L'AUTOCOMPLÉTION (Utilisation du Géo-API de l'État) -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sélection des éléments du DOM
        const input = document.getElementById('localisation-input');
        const resultsContainer = document.getElementById('autocomplete-results');
        
        // ** URL de l'API gouvernementale pour les communes françaises **
        const apiUrl = "https://geo.api.gouv.fr/communes";
        
        let debounceTimeout;

        // Fonction pour masquer les résultats
        const hideResults = () => {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
        };

        // 1. Écoute des saisies dans le champ avec un délai (debounce)
        input.addEventListener('input', () => {
            // Annuler le précédent timer pour attendre la fin de la saisie
            clearTimeout(debounceTimeout);
            
            const query = input.value.trim();

            // Afficher les résultats uniquement si la saisie est d'au moins 2 caractères
            if (query.length < 2) {
                hideResults();
                return;
            }

            // Déclencher la recherche après 300ms
            debounceTimeout = setTimeout(() => {
                fetchAutocompleteResults(query);
            }, 300); 
        });

        // 2. Appel AJAX au Géo-API de l'État via l'API Fetch
        const fetchAutocompleteResults = async (query) => {
            try {
                // Paramètres de l'API : 
                // - nom : le terme recherché (ex: 'ann')
                // - fields : champs à retourner (on veut le nom et le code postal)
                // - limit : nombre de résultats max (max 10)
                // - **boost=population** : pour prioriser les grandes villes comme Annecy
                const url = `${apiUrl}?nom=${encodeURIComponent(query)}&fields=nom,codesPostaux&limit=10&boost=population`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la récupération des communes. Code: ' + response.status);
                }

                // L'API retourne un tableau d'objets (ex: [{nom: "Annecy", codesPostaux: ["74000", ...]}, ...])
                const communes = await response.json();
                displayResults(communes);

            } catch (error) {
                console.error('Erreur Autocomplétion Géo-API:', error);
                hideResults();
            }
        };

        // 3. Affichage des résultats dans le conteneur
        const displayResults = (communes) => {
            resultsContainer.innerHTML = '';
            
            if (communes.length === 0) {
                // Optionnel : afficher un message "Aucun résultat"
                const noResult = document.createElement('div');
                noResult.className = 'px-4 py-2 text-left text-gray-500 italic';
                noResult.textContent = "Aucune ville trouvée.";
                resultsContainer.appendChild(noResult);
                resultsContainer.classList.remove('hidden');
                return;
            }
            
            // Afficher le conteneur des résultats (retirer la classe 'hidden')
            resultsContainer.classList.remove('hidden');

            communes.forEach(commune => {
                // Formater le texte : "Nom de la ville (Code Postal)"
                const nomVille = commune.nom;
                const codePostal = commune.codesPostaux ? commune.codesPostaux[0] : '';
                const displayText = codePostal ? `${nomVille} (${codePostal})` : nomVille;

                const item = document.createElement('div');
                item.className = 'px-4 py-2 cursor-pointer text-left text-gray-700 hover:bg-lbc-orange/10 transition truncate'; // Utilisation de classes Tailwind
                item.textContent = displayText;
                
                // Gérer le clic sur une suggestion
                item.addEventListener('click', () => {
                    // On met uniquement le nom de la ville (sans le CP) dans le champ de saisie
                    input.value = nomVille; 
                    hideResults();       // Masquer la liste
                });
                
                resultsContainer.appendChild(item);
            });
        };

        // 4. Masquer les résultats lorsque l'utilisateur clique en dehors de l'input ou des résultats
        document.addEventListener('click', (event) => {
            const isClickInside = input.contains(event.target) || resultsContainer.contains(event.target);
            if (!isClickInside) {
                hideResults();
            }
        });
        
        // 5. Gérer le focus pour ré-afficher la liste si elle contenait des résultats avant
        input.addEventListener('focus', () => {
            if (resultsContainer.children.length > 0 && input.value.trim().length >= 2) {
                 resultsContainer.classList.remove('hidden');
            }
        });
    });
</script>
@endsection