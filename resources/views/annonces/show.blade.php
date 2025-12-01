@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:underline">Accueil</a> 
        <span class="mx-2">&gt;</span>
        <a href="{{ route('recherche.index') }}" class="hover:underline">Locations</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-800">{{ $annonce->ville->nomville ?? 'France' }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-gray-200 rounded-lg overflow-hidden shadow-sm aspect-video relative group">
                @if($annonce->photos->isNotEmpty())
                    <img src="{{ $annonce->photos->first()->lienurl }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover">
                    <button class="absolute bottom-4 right-4 bg-white/90 text-gray-800 px-4 py-2 rounded-md shadow text-sm font-bold flex items-center gap-2 hover:bg-white">
                        <i class="fa-solid fa-camera"></i> {{ $annonce->photos->count() }} photos
                    </button>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-regular fa-image text-6xl mb-4"></i>
                        <span>Pas de photo disponible</span>
                    </div>
                @endif
            </div>

            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $annonce->titreannonce }}</h1>
                @if($prixMin)
                    <p class="text-xl md:text-2xl font-bold text-lbc-orange">{{ number_format($prixMin, 0, ',', ' ') }} € <span class="text-sm text-gray-500 font-normal">/ nuit</span></p>
                @else
                    <p class="text-gray-500 italic">Prix sur demande</p>
                @endif
                <p class="text-sm text-gray-500 mt-2">
                @if($annonce->dateEnregistrement)
                    Publiée le {{ date('d/m/Y', strtotime($annonce->dateEnregistrement->dateacte)) }}
                @else
                    Date de publication inconnue
                @endif
                </p>
            </div>

            <hr class="border-gray-200">

            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Critères</h2>
                <div class="grid grid-cols-2 gap-y-4 gap-x-8 text-sm">
                    
                    <div>
                        <p class="text-gray-500 mb-1">Type de logement</p>
                        <p class="font-semibold text-gray-800">{{ $annonce->typeHebergement->typehebergement ?? 'Non spécifié' }}</p>
                    </div>

                    @if($annonce->capacite)
                    <div>
                        <p class="text-gray-500 mb-1">Capacité</p>
                        <p class="font-semibold text-gray-800">{{ $annonce->capacite }} voyageurs</p>
                    </div>
                    @endif

                    @if($annonce->nbchambre)
                    <div>
                        <p class="text-gray-500 mb-1">Chambres</p>
                        <p class="font-semibold text-gray-800">{{ $annonce->nbchambre }}</p>
                    </div>
                    @endif

                    @if($annonce->nbetoile)
                    <div>
                        <p class="text-gray-500 mb-1">Classement</p>
                        <p class="font-semibold text-gray-800 text-yellow-500">
                            @for($i=0; $i<$annonce->nbetoile; $i++) ★ @endfor
                        </p>
                    </div>
                    @endif

                </div>

                @if($annonce->equipements->isNotEmpty())
                <div class="mt-6">
                    <p class="text-gray-500 mb-2 text-sm">Équipements</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($annonce->equipements as $equip)
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                <i class="fa-solid fa-check mr-1"></i> {{ $equip->typeequipement }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <hr class="border-gray-200">

            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
                <div class="text-gray-800 leading-relaxed whitespace-pre-line">
                    {{ $annonce->descriptionannonce ?? 'Aucune description fournie pour ce logement.' }}
                </div>
            </div>

            <hr class="border-gray-200">

            <div>
                <h2 class="text-lg font-bold text-gray-900 mb-4">Localisation</h2>
                <p class="font-bold text-gray-800 mb-2"><i class="fa-solid fa-location-dot mr-2"></i> {{ $annonce->ville->nomville ?? 'Ville inconnue' }} ({{ $annonce->ville->cpville ?? '' }})</p>
                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 relative overflow-hidden">
                    <i class="fa-solid fa-map text-4xl"></i>
                    <span class="ml-2">Carte non disponible</span>
                    <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] opacity-10 bg-cover"></div>
                </div>
            </div>

        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-4">
                
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-2xl">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">Hôte Leoncoin</h3>
                            <p class="text-xs text-gray-500">12 annonces • Répond en 1h</p>
                            <div class="flex text-yellow-500 text-xs mt-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                                <span class="text-gray-400 ml-1">(45 avis)</span>
                            </div>
                        </div>
                    </div>
                    
                    <button class="w-full block text-center border-2 border-lbc-orange text-lbc-orange font-bold py-2 rounded-lg hover:bg-orange-50 transition mb-3">
                        <i class="fa-solid fa-envelope mr-2"></i> Envoyer un message
                    </button>
                    <button class="w-full block text-center border border-gray-300 text-gray-700 font-bold py-2 rounded-lg hover:bg-gray-50 transition">
                        <i class="fa-solid fa-phone mr-2"></i> Afficher le numéro
                    </button>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                    @if($prixMin)
                    <div class="flex justify-between items-end mb-4">
                        <span class="font-bold text-2xl text-gray-900">{{ number_format($prixMin, 0, ',', ' ') }} €</span>
                        <span class="text-gray-500 text-sm mb-1">Total pour 1 nuit</span>
                    </div>
                    @endif

                    <button class="w-full bg-lbc-orange hover:bg-lbc-orange_hover text-white font-bold py-3 rounded-lg shadow-md transition transform hover:scale-[1.02]">
                        Réserver / Acheter
                    </button>
                    
                    <p class="text-xs text-center text-gray-400 mt-3">Paiement sécurisé via Leoncoin</p>
                </div>

            </div>
        </div>

    </div>

    <!-- // Annonces similaires -->
    @if($annoncesSimilaires->isNotEmpty())
    <div class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Ces annonces peuvent vous intéresser</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($annoncesSimilaires as $similaire)
                <a href="{{ route('annonces.show', $similaire->idannonce) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($similaire->photos->isNotEmpty())
                            <img src="{{ $similaire->photos->first()->lienurl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900 truncate flex-1 group-hover:text-lbc-blue transition">{{ $similaire->titreannonce }}</h3>
                        </div>

                        @if($similaire->tarifs_min_prixjour)
                            <p class="text-lbc-orange font-bold mt-1">
                                {{ number_format($similaire->tarifs_min_prixjour, 0, ',', ' ') }} € 
                                <span class="text-xs text-gray-500 font-normal">/nuit</span>
                            </p>
                        @else
                            <p class="text-gray-400 text-sm italic mt-1">Prix n.c.</p>
                        @endif

                        <div class="mt-3 flex items-center text-xs text-gray-500">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $similaire->ville->nomville ?? 'Ville inconnue' }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection