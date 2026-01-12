@extends('layouts.app') 

@section('content')

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Succès!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container mx-auto px-4 py-8 h-[calc(100vh-80px)] flex flex-col">
    <div class="bg-white p-6 rounded-lg shadow-md mb-4 flex-shrink-0">
    <form action="{{ route('recherche.index') }}" method="GET" class="flex flex-col gap-4">
        
    <div id="search-filters" class="flex flex-col md:flex-row gap-4">
    
    <div class="flex-1 relative">
        <label for="localisation-input" class="block text-sm font-medium text-gray-700">Destination (Ville)</label>
        
        <input type="text" 
               name="localisation" 
               id="localisation-input" 
               value="{{ request('localisation') }}"
               placeholder="Ex: Toulon, Paris..."
               class="js-autocomplete-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange">
        
        <div class="js-autocomplete-results absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
        </div>
    </div>

    <div class="w-full md:w-1/4">
        <label for="type_hebergement" class="block text-sm font-medium text-gray-700">Type de logement</label>
        <select name="type_hebergement" id="type_hebergement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange">
            <option value="">Tous les types</option>
            @foreach($typesHebergement as $type)
                <option value="{{ $type->idtypehebergement }}" 
                    {{ request('type_hebergement') == $type->idtypehebergement ? 'selected' : '' }}>
                        {{ $type->typehebergement }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-1/4">
        <label for="date_arrivee" class="block text-sm font-bold text-gray-700 mb-1">Date d'arrivée</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-regular fa-calendar text-gray-400"></i>
            </div>
            <input type="date" name="date_arrivee" value="{{ request('date_arrivee') }}" id="input_date_arrivee"
                class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange py-2.5 text-gray-600">
        </div>
    </div>

    <div class="flex flex-col gap-2 w-full md:w-auto">
    
    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-md hover:bg-blue-700 transition font-bold h-full">
        Rechercher
    </button>
    
        <button type="button" 
            id="btn-save-search"
            onclick="prepareAndSubmit()"
            class="border border-gray-300 text-gray-700 px-6 py-2.5 rounded-md hover:bg-gray-50 transition font-medium text-sm flex items-center justify-center gap-2">
            <i class="fa-regular fa-bookmark text-sm"></i>
            Enregistrer la recherche
        </button>
    </div>

    </div>

        <div class="flex justify-end">
            <button type="button" id="toggle-advanced-search" class="text-sm text-blue-600 font-medium hover:text-blue-800 flex items-center gap-2 focus:outline-none">
                <span>Recherche avancée</span>
                <i id="chevron-icon" class="fa-solid fa-chevron-down transition-transform duration-200"></i>
            </button>
        </div>

        <div id="advanced-search-fields" class="hidden border-t border-gray-100 pt-4 mt-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget par nuit (€)</label>
                    <div class="flex items-center gap-2">
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Min</span>
                            <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="0" min="0"
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange">
                        </div>
                        <span class="text-gray-400">-</span>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Max</span>
                            <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Budget max" min="0"
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="chambres" class="block text-sm font-medium text-gray-700 mb-1">Chambres min.</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-bed"></i>
                        </div>
                        <input type="number" name="chambres" id="chambres" value="{{ request('chambres') }}" min="1" max="10"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange"
                            placeholder="Ex: 2">
                    </div>
                </div>

                <div class="mt-4 md:mt-0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options & Règles</label>
                    <div class="flex flex-col gap-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="animaux" value="1" {{ request('animaux') ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-lbc-orange focus:ring-lbc-orange h-4 w-4">
                            <span class="ml-2 text-sm text-gray-600"><i class="fa-solid fa-paw mr-1 text-gray-400"></i> Animaux acceptés</span>
                        </label>
                            
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="fumeur" value="1" {{ request('fumeur') ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-lbc-orange focus:ring-lbc-orange h-4 w-4">
                            <span class="ml-2 text-sm text-gray-600"><i class="fa-solid fa-smoking mr-1 text-gray-400"></i> Fumeurs autorisés</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="save-search-form" action="{{ route('hoistorique.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="localisation" id="hidden_localisation">
        <input type="hidden" name="type_hebergement" id="hidden_type_hebergement">
        <input type="hidden" name="date_arrivee" id="hidden_date_arrivee">
        <input type="hidden" name="prix_min" id="hidden_prix_min">
        <input type="hidden" name="prix_max" id="hidden_prix_max">
        <input type="hidden" name="chambres" id="hidden_chambres">
        <input type="hidden" name="animaux" id="hidden_animaux">
        <input type="hidden" name="fumeur" id="hidden_fumeur">
    </form>
</div>

    <div class="flex flex-col lg:flex-row gap-6 flex-1 overflow-hidden">
        
        <div id="results-container" class="w-full lg:w-1/2 overflow-y-auto pr-2 pb-10">
            <div class="mb-6">
                @if($annonces->count() > 0)
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $annonces->count() }} 
                        {{ Str::plural('annonce', $annonces->count()) }} 
                        {{ Str::plural('trouvée', $annonces->count()) }}
                    </p>
                @else
                    <p class="text-lg font-semibold text-gray-600">Aucun résultat trouvé pour cette recherche.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-6">
                @forelse($annonces as $annonce)
                    
                    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col sm:flex-row h-auto sm:h-48">
                        <div class="w-full sm:w-48 bg-gray-200 overflow-hidden relative flex-shrink-0">
                            @if($annonce->photos->isNotEmpty())
                                <img src="{{ $annonce->photos->first()->lienurl }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-500">Pas de photo</div>
                            @endif

                            <div class="absolute top-2 right-2 z-20">
                                @auth
                                    <form action="{{ route('favoris.toggle', $annonce->idannonce) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:scale-110 transition duration-200 focus:outline-none flex items-center justify-center w-8 h-8 group-btn cursor-pointer" title="Ajouter aux favoris">
                                            @if(Auth::user()->favoris->contains($annonce->idannonce))
                                                <i class="fa-solid fa-heart text-red-500 text-lg"></i>
                                            @else
                                                <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-lg"></i>
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="bg-white rounded-full p-2 shadow-md hover:scale-110 transition duration-200 flex items-center justify-center w-8 h-8 cursor-pointer relative z-20" title="Se connecter pour ajouter aux favoris">
                                        <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-lg"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-blue-600 font-semibold">{{ $annonce->typeHebergement->typehebergement ?? 'Logement' }}</p>
                                    <h3 class="text-lg font-bold text-gray-900 mt-1 line-clamp-1">{{ $annonce->titreannonce }}</h3>
                                </div>
                            
                                <div class="text-right">
                                    @if($annonce->prix_periodes_min_prix) 
                                        <span class="block text-lg font-bold text-gray-900">{{ number_format($annonce->prix_periodes_min_prix, 0, ',', ' ') }} €</span>
                                        <span class="text-xs text-gray-500">par nuit</span>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Prix indisponible</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($annonce->nbetoile)
                                <div class="flex items-center mt-1">
                                    <div class="flex items-center bg-yellow-100 px-2 py-1 rounded text-xs font-bold text-yellow-700 w-fit">
                                        ★ {{ $annonce->nbetoile }}
                                    </div>
                                </div>
                            @endif
                                                                                
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                                {{ $annonce->descriptionannonce }}
                            </p>

                            <div class="mt-2 flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $annonce->ville->nomville ?? 'Ville inconnue' }}
                                </div>
                                <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Voir détails &rarr;</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-lg shadow border border-dashed border-gray-300 text-center col-span-1">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Aucune annonce trouvée</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Veuillez ajuster votre recherche (ville, type de logement ou date).
                        </p>
                    </div>
                @endforelse
            </div>
            
        </div>

        <div id="map-container" class="w-full lg:w-1/2 bg-gray-100 rounded-xl overflow-hidden border border-gray-300 relative z-0 h-64 lg:h-auto">
            <div id="map" class="w-full h-full"></div>
        </div>

    </div>
</div>

<script src="/js/Autocompleteur.js"></script>

<script>
    function prepareAndSubmit() {
        document.getElementById('hidden_localisation').value = document.getElementById('localisation-input').value;
        document.getElementById('hidden_type_hebergement').value = document.getElementById('type_hebergement').value;
    
        const dateInput = document.getElementById('input_date_arrivee');
        if(dateInput) {
             document.getElementById('hidden_date_arrivee').value = dateInput.value;
        }

        document.getElementById('hidden_prix_min').value = document.querySelector('input[name="prix_min"]').value;
        document.getElementById('hidden_prix_max').value = document.querySelector('input[name="prix_max"]').value;
        document.getElementById('hidden_chambres').value = document.getElementById('chambres').value;

        const animCheck = document.querySelector('input[name="animaux"]');
        if(animCheck && animCheck.checked) {
            document.getElementById('hidden_animaux').value = "1";
        } else {
             document.getElementById('hidden_animaux').value = "";
        }

        const fumCheck = document.querySelector('input[name="fumeur"]');
        if(fumCheck && fumCheck.checked) {
            document.getElementById('hidden_fumeur').value = "1";
        } else {
            document.getElementById('hidden_fumeur').value = "";
        }

        document.getElementById('save-search-form').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        var map = L.map('map').setView([46.603354, 1.888334], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var annonces = @json($annonces);
        var markers = [];

        var baseUrl = "{{ url('/annonces') }}";

        annonces.forEach(function(annonce) {

            if(annonce.adresse && annonce.adresse.latitude && annonce.adresse.longitude) {
                var lat = annonce.adresse.latitude;
                var lng = annonce.adresse.longitude;
                
                var prixFormat = '';
                if (annonce.prix_periodes_min_prix) {
                    prixFormat = `<div class="text-lbc-orange font-bold text-base mb-2">${Math.round(annonce.prix_periodes_min_prix)} €</div>`;
                } else {
                    prixFormat = `<div class="text-gray-500 text-sm font-medium mb-2">Prix sur demande</div>`;
                }
                var annonceUrl = baseUrl + '/' + annonce.idannonce;

                var popup = `
                    <div class="text-center">
                        <b class="text-sm block mb-1 text-gray-900">${annonce.titreannonce}</b>
                        <div class="text-lbc-orange font-bold text-base mb-2">${prixFormat}</div>
                        
                        <a href="${annonceUrl}" class="inline-block bg-blue-600 !text-white text-xs font-bold px-3 py-1 rounded hover:bg-blue-700 transition no-underline">
                            Voir l'annonce
                        </a>
                    </div>
                `;

                var marker = L.marker([lat, lng]).addTo(map).bindPopup(popup);
                markers.push(marker);
            }
        });

        if (markers.length > 0) {
            var group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        const input = document.getElementById('localisation-input');
    });

    function formaterDate(date) {
        const annee = date.getFullYear();
        const mois = String(date.getMonth() + 1).padStart(2, '0');
        const jour = String(date.getDate()).padStart(2, '0');
        return `${annee}-${mois}-${jour}`;
    }
    const inputDate = document.getElementById('input_date_arrivee');
    if(inputDate) {
        const aujourdhui = new Date();
        inputDate.min = formaterDate(aujourdhui); 
        const dateDansDeuxAns = new Date(aujourdhui);
        dateDansDeuxAns.setFullYear(aujourdhui.getFullYear() + 2);
        inputDate.max = formaterDate(dateDansDeuxAns);
    }

    document.getElementById('toggle-advanced-search').addEventListener('click', function() {
        const fields = document.getElementById('advanced-search-fields');
        const icon = document.getElementById('chevron-icon');
        
        fields.classList.toggle('hidden');
        
        if (fields.classList.contains('hidden')) {
            icon.classList.remove('rotate-180');
        } else {
            icon.classList.add('rotate-180');
        }
    });

    @if(request('prix_min') || request('prix_max') || request('chambres'))
        document.getElementById('advanced-search-fields').classList.remove('hidden');
        document.getElementById('chevron-icon').classList.add('rotate-180');
    @endif
</script>

@endsection