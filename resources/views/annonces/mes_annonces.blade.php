@extends('layouts.app')

@section('title', 'Mes annonces')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Mes annonces</h1>
            <p class="text-gray-500 text-sm mt-1">Gérez vos biens en location</p>
        </div>
        <a href="{{ route('annonces.create') }}" class="bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i> Déposer une annonce
        </a>
    </div>
        
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-check-circle text-lg"></i> 
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-start gap-2 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
            <div>
                <strong>Action impossible :</strong> {{ $errors->first('error') ?: 'Une erreur est survenue.' }}
            </div>
        </div>
    @endif

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-regular fa-folder-open text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Vous n'avez aucune annonce en ligne</h3>
            <p class="text-gray-500 mb-6">C'est le moment de faire de la place ou de louer votre bien !</p>
            <a href="{{ route('annonces.create') }}" class="text-lbc-blue font-bold hover:underline">
                Commencer maintenant &rarr;
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($annonces as $annonce)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">

                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($annonce->photos->isNotEmpty())
                            <img src="{{ $annonce->photos->first()->lienurl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                <i class="fa-regular fa-image text-3xl"></i>
                            </div>
                        @endif

                        <div class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-md border border-green-200">
                            {{ $annonce->statutannonce ?? 'En ligne' }}
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 truncate mb-1">{{ $annonce->titreannonce }}</h3>
                        <p class="text-sm text-gray-500 mb-3">{{ $annonce->ville->nomville ?? 'Ville inconnue' }}</p>
                        
                        <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                            <div>
                                @if($annonce->prix_periodes_min_prix)
                                    <span class="font-bold text-lbc-orange">{{ number_format($annonce->prix_periodes_min_prix, 0) }} €</span>
                                    <span class="text-xs text-gray-400">/nuit</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Prix n.c.</span>
                                @endif
                            </div>

                        <div class="flex justify-between items-center border-t border-gray-100 pt-4 mt-2">
                            <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="text-gray-600 hover:text-lbc-blue text-sm font-semibold flex items-center gap-1 transition">
                                <i class="fa-regular fa-eye"></i> Voir l'annonce
                            </a>

                            <form action="{{ route('annonces.destroy', $annonce->idannonce) }}" method="POST" 
                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette annonce ?\n\nCette action est irréversible et supprimera toutes les photos associées.');">
                                @csrf
                                @method('DELETE') 
                                
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center gap-1 transition px-2 py-1 rounded hover:bg-red-50">
                                    <i class="fa-regular fa-trash-can"></i> Supprimer
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