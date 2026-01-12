@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Bouton Retour --}}
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-lbc-blue transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- COLONNE GAUCHE : Carte d'identité du Particulier --}}
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 sticky top-6">
                
                {{-- Bandeau décoratif --}}
                <div class="bg-lbc-blue h-24 w-full"></div> 
                
                <div class="px-6 pb-6">
                    {{-- 
                       CORRECTION CSS / LAYOUT :
                       On utilise -mt-12 (marge négative) au lieu de absolute.
                       Cela force le bloc "Avatar" à prendre de la place physiquement,
                       donc le texte en dessous ne pourra plus jamais remonter dessus.
                    --}}
                    <div class="-mt-12 flex justify-center relative z-10">
                        <div class="w-24 h-24 bg-white rounded-full p-1 shadow-md">
                            <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden text-2xl font-bold text-gray-400 uppercase">
                                
                                @php
                                    $photoUrl = null;

                                    // 1. Essai direct sur le particulier
                                    if (optional($proprietaireperso->photo)->lienurl) {
                                        $photoUrl = $proprietaireperso->photo->lienurl;
                                    }
                                    // 2. Essai via une relation parente (ex: compteUtilisateur, user, ou proprietaire)
                                    // Si votre relation s'appelle autrement, adaptez ici (ex: ->user->photo)
                                    elseif (optional($proprietaireperso->proprietaire)->photo && optional($proprietaireperso->proprietaire->photo)->lienurl) {
                                        $photoUrl = $proprietaireperso->proprietaire->photo->lienurl;
                                    }
                                    elseif (optional($proprietaireperso->compteUtilisateur)->photo && optional($proprietaireperso->compteUtilisateur->photo)->lienurl) {
                                        $photoUrl = $proprietaireperso->compteUtilisateur->photo->lienurl;
                                    }
                                @endphp

                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    {{ substr($proprietaireperso->prenomparticulier, 0, 1) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Infos du profil --}}
                    {{-- Avec la modif CSS ci-dessus, un simple mt-4 suffit ici --}}
                    <div class="mt-4 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 capitalize">
                            {{ $proprietaireperso->prenomparticulier }} {{ $proprietaireperso->nomparticulier }}
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Particulier</p>

                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                <span class="text-gray-600">Annonces en ligne</span>
                                <span class="font-bold text-lbc-blue">{{ $proprietaireperso->annoncedumemeprop->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Membre depuis</span>
                                <span class="text-gray-800 text-sm">
                                    {{ $proprietaireperso->created_at ? $proprietaireperso->created_at->format('d/m/Y') : 'N/C' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLONNE DROITE : Liste des annonces --}}
        <div class="w-full lg:w-2/3">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fa-solid fa-list-ul mr-2 text-lbc-blue"></i>
                Les annonces de {{ $proprietaireperso->prenomparticulier }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($proprietaireperso->annoncedumemeprop as $annonce)
                    <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                        
                        {{-- Image de l'annonce --}}
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($annonce->photos->isNotEmpty())
                                <img src="{{ $annonce->photos->first()->lienurl }}" alt="{{ $annonce->titreannonce }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                                    <i class="fa-regular fa-image text-3xl"></i>
                                </div>
                            @endif
                            
                            @if($annonce->photos->count() > 1)
                                <div class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm">
                                    <i class="fa-solid fa-camera mr-1"></i> {{ $annonce->photos->count() }}
                                </div>
                            @endif
                        </div>

                        {{-- Contenu de l'annonce --}}
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-900 truncate group-hover:text-lbc-blue transition text-lg mb-1">
                                {{ $annonce->titreannonce }}
                            </h3>
                            
                            <div class="mb-3">
                                @if($annonce->prix_periodes_min_prix)
                                    <p class="text-lbc-orange font-bold text-lg">
                                        {{ number_format($annonce->prix_periodes_min_prix, 0, ',', ' ') }} € 
                                        <span class="text-xs text-gray-500 font-normal">/nuit</span>
                                    </p>
                                @else
                                    <p class="text-gray-400 text-sm italic">Prix non renseigné</p>
                                @endif
                            </div>

                            <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center truncate max-w-[60%]">
                                    <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> 
                                    {{ $annonce->ville->nomville ?? 'Localisation' }}
                                </span>
                                <span>
                                    {{ (isset($annonce->dateEnregistrement) && $annonce->dateEnregistrement->dateacte) ? \Carbon\Carbon::parse($annonce->dateEnregistrement->dateacte)->diffForHumans() : '' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-1 md:col-span-2 py-12 px-6 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                            <i class="fa-regular fa-folder-open text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Aucune annonce</h3>
                        <p class="mt-1 text-gray-500">Cet utilisateur n'a pas encore publié d'autres annonces.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection