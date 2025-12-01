@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 mt-6">
    <div class="bg-lbc-orange/10 rounded-2xl p-8 md:p-12 text-center relative overflow-hidden">
        
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">
            Des millions de petites annonces et autant d’occasions de se faire plaisir.
        </h1>

        <div class="bg-white p-4 rounded-xl shadow-lg max-w-4xl mx-auto relative z-10">
            <form action="{{ route('recherche.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center">
                
                <div class="flex-1 w-full relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-location-dot text-gray-400 group-focus-within:text-gray-800"></i>
                    </div>
                    <input type="text" name="localisation" 
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange sm:text-sm transition" 
                           placeholder="Où cherchez-vous ? (Ville)">
                </div>

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

<div class="container mx-auto px-4 mt-12">
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
</div>

<div class="container mx-auto px-4 mt-16 mb-12">
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-xl font-bold text-gray-900">Dernières annonces publiées</h2>
        <a href="{{ route('recherche.index') }}" class="text-lbc-blue font-semibold hover:underline text-sm">Voir tout</a>
            
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($dernieresAnnonces as $annonce)
            <a href="#" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden">
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
                    
                    @if($annonce->tarifs_min_prixjour)
                        <p class="text-lbc-orange font-bold mt-1">{{ $annonce->tarifs_min_prixjour }} € <span class="text-xs text-gray-500 font-normal">/nuit</span></p>
                    @else
                        <p class="text-gray-400 text-sm italic mt-1">Prix non renseigné</p>
                    @endif

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $annonce->ville->nomville ?? 'Localisation' }}
                        </span>
                        <!-- <span>{{-- Date ici  --}} Aujourd'hui</span> -->
                        <span>
                                {{ $annonce->dateEnregistrement->dateacte ?? 'Date inconnue' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500">
                <p>Aucune annonce pour le moment. Soyez le premier à déposer !</p>
            </div>
        @endforelse
    </div>
</div>

@endsection