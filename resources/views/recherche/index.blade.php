@extends('layouts.app') 

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container mx-auto px-4 py-8 h-[calc(100vh-80px)] flex flex-col">
    <div class="bg-white p-6 rounded-lg shadow-md mb-4 flex-shrink-0">
        <form action="{{ route('recherche.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            
            <div class="flex-1">
                <label for="localisation" class="block text-sm font-medium text-gray-700">Destination (Ville)</label>
                <input type="text" name="localisation" id="localisation" 
                       value="{{ request('localisation') }}"
                       placeholder="Ex: Toulon, Paris..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="w-full md:w-1/4">
                <label for="type_hebergement" class="block text-sm font-medium text-gray-700">Type de logement</label>
                <select name="type_hebergement" id="type_hebergement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                        <input type="date" name="date_arrivee" value="{{ request('date_arrivee') }}"
                            class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:border-lbc-orange focus:ring-lbc-orange py-2.5 text-gray-600">
                    </div>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                    Rechercher
                </button>
            </div>
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 flex-1 overflow-hidden">
        
        <div class="w-full lg:w-1/2 overflow-y-auto pr-2 pb-10">
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
                        </div>

                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-blue-600 font-semibold">{{ $annonce->typeHebergement->typehebergement ?? 'Logement' }}</p>
                                    <h3 class="text-lg font-bold text-gray-900 mt-1 line-clamp-1">{{ $annonce->titreannonce }}</h3>
                                </div>
                    
                                <div class="text-right">
                                    @if($annonce->tarifs_min_prixjour) 
                                    <span class="block text-lg font-bold text-gray-900">{{ number_format($annonce->tarifs_min_prixjour, 0) }} €</span>
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
                    @endforelse
            </div>
            
            </div>

        <div class="w-full lg:w-1/2 bg-gray-100 rounded-xl overflow-hidden border border-gray-300 relative z-0 h-64 lg:h-auto">
            <div id="map" class="w-full h-full"></div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([46.603354, 1.888334], 6); // Centre France
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var annonces = @json($annonces);
        var markers = [];

        annonces.forEach(function(annonce) {
            if(annonce.ville && annonce.ville.latitude && annonce.ville.longitude) {
                var lat = annonce.ville.latitude;
                var lng = annonce.ville.longitude;
                var prix = annonce.tarifs_min_prixjour ? Math.round(annonce.tarifs_min_prixjour) + ' €' : 'Voir';

                var popup = `
                    <div class="text-center">
                        <b class="text-sm block mb-1">${annonce.titreannonce}</b>
                        <span class="text-lbc-orange font-bold text-base block">${prix}</span>
                        <a href="/annonces/${annonce.idannonce}" class="text-blue-600 underline text-xs mt-1 block">Détails</a>
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
    });
</script>

@endsection