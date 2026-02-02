@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container mx-auto px-4 py-8">
    
    {{-- NAVIGATION --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:underline">Accueil</a> 
        <span class="mx-2">&gt;</span>
        <a href="{{ route('recherche.index') }}" class="hover:underline">Locations</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-800">{{ $annonce->ville->nomville ?? 'France' }}</span>
    </nav>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            {{-- IMAGE PRINCIPALE ET BOUTON GALERIE --}}
            <div id="gallery-trigger" class="bg-gray-200 rounded-lg overflow-hidden shadow-sm aspect-video relative group">
                @if($annonce->photos->isNotEmpty())
                    <img src="{{ asset($annonce->photos->first()->lienurl) }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover">
                    
                    <button id="open-gallery-button" type="button" class="absolute bottom-4 right-4 bg-white/90 text-gray-800 px-4 py-2 rounded-md shadow text-sm font-bold flex items-center gap-2 hover:bg-white cursor-pointer">
                        <i class="fa-solid fa-camera"></i> {{ $annonce->photos->count() }} photos
                    </button>
                    
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-regular fa-image text-6xl mb-4"></i>
                        <span>Pas de photo disponible</span>
                    </div>
                @endif
            </div>

            {{-- TITRE ET GARANTIE --}}
            <div id="annonce-header">
                <div class="flex items-start justify-between flex-wrap gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        {{ $annonce->titreannonce }}
                        
                        {{-- BADGE GARANTIE : Affiché si l'annonce est spécifiquement garantie par le service --}}
                        @if($annonce->est_garantie)
                            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full border border-green-200 shadow-sm align-middle" title="Annonce garantie par le service">
                                <i class="fa-solid fa-shield-halved"></i> 
                                <span class="font-bold">Garantie</span>
                            </span>
                        @endif
                    </h1>
                </div>

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

            <div id="annonce-specs">
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

                {{-- RÈGLEMENT INTÉRIEUR / CONDITIONS --}}
                @if($annonce->conditionHebergement)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Règlement intérieur</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Horaires --}}
                        <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-white text-gray-600 shadow-sm flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Horaires</p>
                                <div class="text-sm text-gray-800">
                                    <span class="font-bold">Arrivée :</span> 
                                    {{ \Carbon\Carbon::parse($annonce->conditionHebergement->heurearrivee)->format('H:i') }}
                                </div>
                                <div class="text-sm text-gray-800">
                                    <span class="font-bold">Départ :</span> 
                                    {{ \Carbon\Carbon::parse($annonce->conditionHebergement->heuredepart)->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        {{-- Animaux & Fumeur --}}
                        <div class="space-y-3">
                            {{-- Animaux --}}
                            <div class="flex items-center justify-between p-2 rounded {{ $annonce->conditionHebergement->animauxacceptes ? 'bg-green-50' : 'bg-red-50' }}">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-paw {{ $annonce->conditionHebergement->animauxacceptes ? 'text-green-600' : 'text-red-500' }}"></i>
                                    <span class="text-sm font-medium {{ $annonce->conditionHebergement->animauxacceptes ? 'text-green-800' : 'text-red-800' }}">Animaux</span>
                                </div>
                                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $annonce->conditionHebergement->animauxacceptes ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $annonce->conditionHebergement->animauxacceptes ? 'AUTORISÉS' : 'INTERDITS' }}
                                </span>
                            </div>

                            {{-- Fumeur --}}
                            <div class="flex items-center justify-between p-2 rounded {{ $annonce->conditionHebergement->fumeur ? 'bg-green-50' : 'bg-red-50' }}">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-smoking {{ $annonce->conditionHebergement->fumeur ? 'text-green-600' : 'text-red-500' }}"></i>
                                    <span class="text-sm font-medium {{ $annonce->conditionHebergement->fumeur ? 'text-green-800' : 'text-red-800' }}">Fumeurs</span>
                                </div>
                                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $annonce->conditionHebergement->fumeur ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $annonce->conditionHebergement->fumeur ? 'AUTORISÉS' : 'INTERDITS' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
                @endif
                {{-- FIN RÈGLEMENT INTÉRIEUR --}}
                
                {{-- ÉQUIPEMENTS (Inchangé) --}}
                @if($annonce->equipements->isNotEmpty())
                <div class="mt-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Équipements</h2>
                    @php $equipementsGroupes = $annonce->equipements->groupBy('idtypeequipement'); @endphp
                    <div class="space-y-4">
                        @foreach($equipementsGroupes as $idType => $groupeEquipements)
                            @php
                                $premierEquipement = $groupeEquipements->first();
                                $typeNom = $premierEquipement->typeEquipement->typeequipement ?? 'Autre';
                            @endphp
                            <details class="group border border-gray-200 rounded-lg overflow-hidden transition-all duration-300 open:shadow-lg">
                                <summary class="flex justify-between items-center cursor-pointer bg-gray-50 hover:bg-gray-100 px-4 py-3 font-semibold text-gray-800 transition-colors">
                                    <span>{{ $typeNom }} ({{ $groupeEquipements->count() }})</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transform transition-transform duration-300 group-open:rotate-180"></i>
                                </summary>
                                <div class="p-4 border-t border-gray-100">
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($groupeEquipements as $equipement)
                                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                                                <i class="fa-solid fa-bolt text-blue-500 mr-1.5"></i> {{ $equipement->nomequipement }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- SERVICES (Inchangé) --}}
                @if($annonce->services->isNotEmpty())
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Services & Commodités</h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($annonce->services as $service)
                            <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                                <i class="fa-solid fa-check mr-1.5"></i> {{ $service->nomservice }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div> 

            <hr class="border-gray-200">

            {{-- DESCRIPTION --}}
            <div id="annonce-description">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
                <div class="text-gray-800 leading-relaxed whitespace-pre-line">
                    {{ $annonce->descriptionannonce ?? 'Aucune description fournie pour ce logement.' }}
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- LOCALISATION --}}
            <div id="annonce-map">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Localisation</h2>
                <p class="font-bold text-gray-800 mb-2"><i class="fa-solid fa-location-dot mr-2"></i> {{ $annonce->ville->nomville ?? 'Ville inconnue' }} ({{ $annonce->ville->cpville ?? '' }})</p>
                <div id="map-detail" class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 relative z-0"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var lat = @json($annonce->adresse?->latitude);
                        var lng = @json($annonce->adresse?->longitude);
                        var titre = @json($annonce->titreannonce);
                        var ville = @json($annonce->ville->nomville ?? '');

                        if (lat && lng) {
                            var map = L.map('map-detail').setView([lat, lng], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
                            L.marker([lat, lng]).addTo(map).bindPopup("<b>" + titre + "</b><br>" + ville).openPopup();
                        } else {
                            document.getElementById('map-detail').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Carte non disponible</div>';
                        }
                    });
                </script>
            </div>
        </div>

        {{-- COLONNE LATÉRALE (PROPRIÉTAIRE ET ADMINISTRATION) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">

                <div id="sidebar-owner" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 transition-shadow hover:shadow-md">
            
                    <div class="flex items-start gap-4 mb-6">
                        <div class="shrink-0 relative">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-2xl font-bold uppercase overflow-hidden ring-2 ring-white shadow-sm">
                                @if($annonce->proprietaire && $annonce->proprietaire->photo && $annonce->proprietaire->photo->lienurl)
                                    <img src="{{ asset($annonce->proprietaire->photo->lienurl) }}" alt="Avatar" class="w-full h-full object-cover transition transform hover:scale-110 duration-500">
                                @else
                                    {{ substr($annonce->proprietaire->nom_affichage ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h3 class="font-bold text-gray-900 text-lg truncate">
                                    {{ $annonce->proprietaire->nom_affichage ?? 'Utilisateur Inconnu' }}
                                </h3>
                                @if($annonce->proprietaire && $annonce->proprietaire->professionnel)
                                    <span class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">PRO</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded-full font-medium">Particulier</span>
                                @endif
                            </div>
                            
                            {{-- Info sur la vérification du téléphone (Visible par tous) --}}
                            @if($annonce->proprietaire && $annonce->proprietaire->telephone_verifie)
                                <div class="mb-1">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded">
                                        <i class="fa-solid fa-check-circle"></i> Tél. Vérifié
                                    </span>
                                </div>
                            @endif

                            <div class="flex items-center text-yellow-400 text-xs mb-1">
                                <div class="flex space-x-0.5">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <span class="text-gray-400 font-medium ml-1.5 underline decoration-gray-300 hover:text-gray-600 cursor-pointer">12 avis</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5 text-center">
                        @if($annonce->proprietaire && $annonce->proprietaire->professionnel)
                            <a href="{{ route('professionnels.show', $annonce->proprietaire->professionnel->idprofessionnel) }}" class="inline-flex items-center text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">
                                Voir le profil complet <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                            </a>
                        @elseif($annonce->proprietaire && $annonce->proprietaire->particulier)
                            <a href="{{ route('particulier.show', $annonce->proprietaire->particulier->idparticulier) }}" class="inline-flex items-center text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">
                                Voir le profil complet <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                            </a>
                        @endif
                    </div>
                    
                    <button class="w-full block text-center border-2 border-lbc-orange text-lbc-orange font-bold py-2 rounded-lg hover:bg-orange-50 transition mb-3">
                        <i class="fa-solid fa-envelope mr-2"></i> Envoyer un message
                    </button>
                    
                    <button class="w-full group relative overflow-hidden flex items-center justify-center border border-gray-200 bg-white text-gray-700 font-bold py-2.5 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all">
                        <span class="flex items-center gap-2 group-hover:opacity-0 transition-opacity duration-200">
                            <i class="fa-solid fa-phone text-gray-400"></i> 
                            {{ substr($annonce->proprietaire->telutilisateur ?? '0600000000', 0, 4) }}.. .. ..
                        </span>
                        <span class="absolute inset-0 flex items-center justify-center gap-2 text-gray-900 bg-gray-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 transform translate-y-2 group-hover:translate-y-0">
                            <i class="fa-solid fa-phone text-green-600"></i>
                            {{ $annonce->proprietaire->telutilisateur ?? 'Non renseigné' }}
                        </span>
                    </button>

                    
                    
                    @auth
                        {{-- 1. CAS ADMIN : Vérification du téléphone du propriétaire --}}
                        @if(Auth::user()->isAdmin())
                            <div class="border-t border-gray-200 pt-4 mt-4 bg-red-50 p-4 rounded-lg border border-red-100">
                                <p class="text-xs font-extrabold text-red-600 uppercase mb-3 flex items-center gap-1">
                                    <i class="fa-solid fa-user-lock"></i> Administration
                                </p>
                                <p class="text-[10px] text-gray-500 mb-2">Gérer le statut du vendeur :</p>
                                
                                <form action="{{ route('admin.verify_phone', $annonce->proprietaire->idutilisateur) }}" method="POST">
                                    @csrf
                                    @if($annonce->proprietaire->telephone_verifie)
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs bg-white border border-red-300 text-red-600 py-2.5 rounded-lg hover:bg-red-50 transition shadow-sm font-bold">
                                            <i class="fa-solid fa-ban"></i> Invalider Téléphone
                                        </button>
                                    @else
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs bg-red-600 text-white py-2.5 rounded-lg hover:bg-red-700 transition shadow-md font-bold">
                                            <i class="fa-solid fa-check"></i> Valider Téléphone
                                        </button>
                                    @endif
                                </form>
                            </div>
                        @endif

                        {{-- 2. CAS SERVICE ANNONCE : Garantie de l'annonce spécifique --}}
                        {{-- CORRECTION : On cache ce bloc pour l'admin si le téléphone n'est pas vérifié (pour éviter le doublon d'erreur) --}}
                        @if(Auth::user()->isServiceAnnonce() && (!Auth::user()->isAdmin() || $annonce->proprietaire->telephone_verifie))
                            <div class="border-t border-gray-200 pt-4 mt-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <p class="text-xs font-extrabold text-blue-700 uppercase mb-3 flex items-center gap-1">
                                    <i class="fa-solid fa-clipboard-check"></i> Service Annonces
                                </p>

                                {{-- On vérifie d'abord si le téléphone est OK --}}
                                @if($annonce->proprietaire->telephone_verifie)
                                    
                                    <p class="text-[10px] text-green-600 mb-2 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Vendeur vérifié par Admin.
                                    </p>

                                    {{-- Action sur la garantie de l'annonce --}}
                                    <form action="{{ route('admin.garantir_annonce', $annonce->idannonce) }}" method="POST">
                                        @csrf
                                        @if($annonce->est_garantie)
                                            <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs bg-white border border-blue-300 text-blue-600 py-2.5 rounded-lg hover:bg-blue-50 transition shadow-sm font-bold">
                                                <i class="fa-solid fa-minus-circle"></i> Retirer la Garantie
                                            </button>
                                        @else
                                            <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs bg-blue-600 text-white py-2.5 rounded-lg hover:bg-blue-700 transition shadow-md font-bold">
                                                <i class="fa-solid fa-shield-halved"></i> Garantir cette Annonce
                                            </button>
                                        @endif
                                    </form>

                                @else
                                    {{-- Cas bloquant : Téléphone non vérifié (Visible uniquement pour les membres non-admin du service) --}}
                                    <div class="bg-white p-2 rounded border border-gray-200 text-center">
                                        <i class="fa-solid fa-triangle-exclamation text-orange-500 text-lg mb-1 block"></i>
                                        <p class="text-[10px] text-gray-500 leading-tight">
                                            Impossible de garantir cette annonce.<br>
                                            <strong>Le téléphone du vendeur n'a pas été validé par un administrateur.</strong>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endauth
                    {{-- ======================================================== --}}

                </div>

                @php
                    $avisExpertPositif = $annonce->avis->firstWhere('avis_expert', 'Positif');
                @endphp

                @if($avisExpertPositif)
                    <div class="mb-4 bg-purple-50 border border-purple-200 p-3 rounded-lg flex items-start gap-3">
                        <div class="bg-purple-100 p-2 rounded-full text-purple-600 mt-1">
                            <i class="fa-solid fa-award text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-purple-800 uppercase tracking-wider">Recommandé par nos experts</p>
                            <p class="text-sm text-purple-900 font-medium">Ce bien a reçu un avis positif du Service Immobilier.</p>
                            <p class="text-xs text-purple-700 mt-1 italic">"{{ $avisExpertPositif->commentaire ?? 'Annonce validée.' }}"</p>
                        </div>
                    </div>
                @endif
                {{-- PAIEMENT --}}
                <div id="booking-action" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                    @if($prixMin)
                    <div class="flex items-end gap-2 mb-6">
                        <span class="font-extrabold text-3xl text-gray-900 tracking-tight">{{ number_format($prixMin, 0, ',', ' ') }} €</span>
                        <span class="text-gray-500 font-medium mb-1.5">/ nuit</span>
                    </div>
                    @endif
                    @auth
                        {{-- Cas 1 : Connecté -> Vers Réservation 
                        <a href="{{ route('annonce.paiement', ['id' => $annonce->idannonce]) }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold text-lg py-3.5 rounded-xl shadow-lg shadow-orange-600/20 transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer no-underline">
                            Réserver / Acheter
                        </a>
                        --}}
                        <a href="{{ route('reservations.create', ['id' => $annonce->idannonce]) }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold text-lg py-3.5 rounded-xl shadow-lg shadow-orange-600/20 transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer no-underline">
                        Réserver / Acheter
                    </a>
                    @else
                        {{-- Cas 2 : Non connecté -> Vers Connexion  --}}
                        <a href="{{ route('reservations.create', ['id' => $annonce->idannonce]) }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white text-center font-bold text-lg py-3.5 rounded-xl shadow-lg shadow-orange-600/20 transition transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer no-underline">
                            Se connecter pour réserver
                        </a>
                        
                        
                    @endauth

                    {{-- Calendrier des disponibilités --}}


                    <a href="{{ route('annonce.calendar', ['id' => $annonce->idannonce]) }}"> Voir le calendrier des disponibilités</a>

                    

                    
                    <div class="flex items-center justify-center gap-2 mt-4 text-xs text-gray-400">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Paiement 100% sécurisé</span>
                    </div>
                </div>


                
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mt-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-lbc-blue"></i>
                        Partager cette annonce
                    </h3>
                    <div class="flex justify-around">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('annonces.show', $annonce->idannonce)) }}" 
                        target="_blank" 
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition"
                        title="Partager sur Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <!-- Twitter / X -->
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('annonces.show', $annonce->idannonce)) }}&text={{ urlencode($annonce->titreannonce) }}" 
                        target="_blank" 
                        class="w-10 h-10 rounded-full bg-gray-100 text-gray-800 flex items-center justify-center hover:bg-black hover:text-white transition"
                        title="Partager sur X (Twitter)">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                class="h-4 w-4 fill-current" 
                                viewBox="0 0 512 512">
                                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                            </svg>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($annonce->titreannonce . ' ' . route('annonces.show', $annonce->idannonce)) }}" 
                        target="_blank" 
                        class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white transition"
                        title="Partager sur WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                        <!-- Email -->
                        <a href="mailto:?subject={{ urlencode('Regarde cette annonce : ' . $annonce->titreannonce) }}&body={{ urlencode(route('annonces.show', $annonce->idannonce)) }}"
                        class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition"
                        title="Partager par Email">
                            <i class="fa-regular fa-envelope"></i>
                        </a>

                        <!-- SMS (Mobile) -->
                        <a href="sms:?body={{ urlencode('Regarde cette annonce : ' . $annonce->titreannonce . ' ' . route('annonces.show', $annonce->idannonce)) }}"
                        class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition md:hidden"
                        title="Partager par Message">
                            <i class="fa-solid fa-comment-sms"></i>
                        </a>
                        
                        <!-- Copy Link -->
                        <button onclick="navigator.clipboard.writeText('{{ route('annonces.show', $annonce->idannonce) }}'); alert('Lien copié !');" 
                                class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-600 hover:text-white transition"
                                title="Copier le lien">
                            <i class="fa-solid fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ANNONCES SIMILAIRES --}}
    @if($annoncesSimilaires->isNotEmpty())
    <div class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Ces annonces peuvent vous intéresser</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($annoncesSimilaires as $similaire)
                <a href="{{ route('annonces.show', $similaire->idannonce) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($similaire->photos->isNotEmpty())
                            <img src="{{ asset($similaire->photos->first()->lienurl) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900 truncate flex-1 group-hover:text-lbc-blue transition">{{ $similaire->titreannonce }}</h3>
                        </div>

                        @if($similaire->prix_periodes_min_prix)
                            <p class="text-lbc-orange font-bold mt-1">{{ number_format($similaire->prix_periodes_min_prix, 0, ',', ' ') }} € <span class="text-xs text-gray-500 font-normal">/nuit</span></p>
                        @else
                            <p class="text-gray-400 text-sm italic mt-1">Prix n.c.</p>
                        @endif
                        <div class="mt-3 flex items-center text-xs text-gray-500"><i class="fa-solid fa-location-dot mr-1"></i> {{ $similaire->ville->nomville ?? 'Ville inconnue' }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>


<div id="photo-gallery-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-7xl mx-auto">
            <button id="close-gallery-button" class="absolute top-0 right-0 m-4 text-white text-3xl font-bold hover:text-gray-300 z-50 cursor-pointer">&times;</button>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-8">
                @if($annonce->photos->isNotEmpty())
                    @foreach($annonce->photos as $photo)
                        <div class="relative overflow-hidden rounded-lg shadow-xl aspect-square">
                            <img src="{{ asset($photo->lienurl) }}" class="w-full h-full object-cover transform transition duration-300 hover:scale-105 cursor-zoom-in">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openBtn = document.getElementById('open-gallery-button');
        const closeBtn = document.getElementById('close-gallery-button');
        const modal = document.getElementById('photo-gallery-modal');

        if (openBtn && modal) {
            openBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        if (closeBtn && modal) {
            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) { if (e.target.id === 'photo-gallery-modal') closeModal(); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });
        }
    });
</script>

@endsection