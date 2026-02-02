@extends('layouts.app')

@section('title', 'Mes favoris')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans mb-8">Mes favoris</h1>

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-regular fa-heart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun favori pour l'instant</h3>
            <a href="{{ route('recherche.index') }}" class="text-lbc-blue font-bold hover:underline">Découvrir des annonces</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="block group">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($annonce->photos->isNotEmpty())
                                <img src="{{ $annonce->photos->first()->lienurl }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100"><i class="fa-solid fa-image text-3xl"></i></div>
                            @endif
                            <div class="absolute top-2 right-2 text-red-500 bg-white rounded-full p-1.5 shadow-sm">
                                <i class="fa-solid fa-heart"></i>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 truncate mb-1">{{ $annonce->titreannonce }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $annonce->ville->nomville ?? '' }}</p>
                            @php $prix = $annonce->prixPeriodes?->min('prix'); @endphp
                            @if($prix)
                                <span class="font-bold text-lbc-orange">{{ number_format($prix, 0) }} €</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection