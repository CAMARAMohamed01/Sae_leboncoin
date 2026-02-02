@extends('layouts.app')

@section('title', 'Validation des Annonces')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-orange-100 text-orange-600 rounded-xl">
            <i class="fa-solid fa-list-check text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Modération des annonces</h1>
            <p class="text-gray-500 mt-1">
                Liste des annonces déposées en attente de validation.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-solid fa-check-double text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tout est à jour !</h3>
            <p class="text-gray-500">Aucune annonce en attente de validation pour le moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($annonces as $annonce)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
                    
                    {{-- Image --}}
                    <div class="w-full md:w-48 h-32 md:h-auto bg-gray-200 relative flex-shrink-0">
                        @if($annonce->photos->isNotEmpty())
                            <img src="{{ asset($annonce->photos->first()->lienurl) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                        <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">
                            ID: {{ $annonce->idannonce }}
                        </div>
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 p-4 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg line-clamp-1">
                                        <a href="{{ route('annonces.show', $annonce->idannonce) }}" target="_blank" class="hover:underline hover:text-lbc-blue">
                                            {{ $annonce->titreannonce }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-2">
                                        <span><i class="fa-solid fa-user"></i> {{ $annonce->proprietaire->nom_affichage ?? 'Inconnu' }}</span>
                                        <span class="text-gray-300">|</span>
                                        <span><i class="fa-solid fa-location-dot"></i> {{ $annonce->ville->nomville ?? '?' }}</span>
                                        <span class="text-gray-300">|</span>
                                        <span><i class="fa-regular fa-clock"></i> {{ $annonce->dateEnregistrement ? \Carbon\Carbon::parse($annonce->dateEnregistrement->dateacte)->format('d/m/Y') : '-' }}</span>
                                    </p>
                                </div>
                                <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded border border-orange-200 uppercase">
                                    En attente
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 line-clamp-2 italic bg-gray-50 p-2 rounded border border-gray-100">
                                "{{ $annonce->descriptionannonce }}"
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end items-center gap-3 mt-3 pt-3 border-t border-gray-100">
                            
                            <form action="{{ route('admin.annonces.refuser', $annonce->idannonce) }}" method="POST" onsubmit="return confirm('Refuser cette annonce ?');">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-bold text-red-600 bg-white border border-red-200 rounded hover:bg-red-50 transition">
                                    <i class="fa-solid fa-xmark"></i> Refuser
                                </button>
                            </form>

                            <form action="{{ route('admin.annonces.valider', $annonce->idannonce) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-green-600 rounded hover:bg-green-700 transition shadow-sm">
                                    <i class="fa-solid fa-check"></i> Valider et Mettre en ligne
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection