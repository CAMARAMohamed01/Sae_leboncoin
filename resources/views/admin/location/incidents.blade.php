@extends('layouts.app')

@section('title', 'Gestion des Incidents')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-red-100 text-red-600 rounded-xl">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Incidents en cours</h1>
            <p class="text-gray-500 mt-1">
                Espace Service Location - Traitement des litiges
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($incidents->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-solid fa-clipboard-check text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun incident à traiter</h3>
            <p class="text-gray-500">Tout se passe bien pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($incidents as $incident)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                                    Incident #{{ $incident->idincident }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Type : {{ ucfirst($incident->typeincident) }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Déclaré le {{ \Carbon\Carbon::parse($incident->datedeclaration)->format('d/m/Y') }} 
                                    par <strong>{{ $incident->declarant->nom_affichage ?? 'Utilisateur' }}</strong>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-700">
                                    Concerne l'annonce :
                                </p>
                                <a href="{{ route('annonces.show', $incident->reservation->annonce->idannonce ?? 0) }}" class="text-lbc-blue hover:underline text-sm">
                                    {{ $incident->reservation->annonce->titreannonce ?? 'Annonce supprimée' }}
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                            <p class="text-sm text-gray-700 italic">
                                "<span class="font-medium">{{ $incident->description }}</span>"
                            </p>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                <i class="fa-regular fa-envelope mr-1"></i> Contacter les parties
                            </button>

                            <form action="{{ route('admin.location.classer', $incident->idincident) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir classer cet incident sans suite ? Il sera clôturé.');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-bold hover:bg-gray-900 transition shadow-sm flex items-center gap-2">
                                    <i class="fa-solid fa-box-archive"></i> Classer sans suite
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