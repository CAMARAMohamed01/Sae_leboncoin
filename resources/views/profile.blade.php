@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto px-4 mt-8 mb-12">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon compte</h1>
        <p class="text-gray-600">Gérez vos informations et consultez vos annonces.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-20 bg-lbc-blue/10"></div>
                
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-white rounded-full mx-auto p-1 shadow-md mb-4 flex items-center justify-center">
                        <div class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-4xl">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    
                    {{-- Utilisation de Auth::user() pour récupérer les infos --}}
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ Auth::user()->prenom ?? 'Utilisateur' }} {{ Auth::user()->nom ?? '' }}
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">Membre depuis {{ Auth::user()->created_at ? Auth::user()->created_at->format('Y') : '2025' }}</p>
                    
                    <div class="flex justify-center gap-2 mb-6">
                        <span class="px-3 py-1 bg-lbc-orange/10 text-lbc-orange text-xs font-bold rounded-full">
                            Particulier
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 pt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 text-red-500 hover:text-red-700 font-semibold transition-colors py-2 rounded-lg hover:bg-red-50">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Se déconnecter
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <nav class="flex flex-col">
                    <a href="#" class="flex items-center gap-3 px-6 py-4 bg-lbc-orange/5 text-lbc-orange font-semibold border-l-4 border-lbc-orange">
                        <i class="fa-solid fa-id-card w-5"></i>
                        Mes informations
                    </a>
                    <a href="#" class="flex items-center gap-3 px-6 py-4 text-gray-600 hover:bg-gray-50 transition border-l-4 border-transparent">
                        <i class="fa-solid fa-layer-group w-5"></i>
                        Mes annonces 
                        <span class="ml-auto bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs font-bold">0</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-6 py-4 text-gray-600 hover:bg-gray-50 transition border-l-4 border-transparent">
                        <i class="fa-regular fa-heart w-5"></i>
                        Mes favoris
                    </a>
                </nav>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Mes informations personnelles</h3>
                    <button class="text-lbc-blue hover:underline text-sm font-semibold">Modifier</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Identifiant (Email)</label>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                            {{ Auth::user()->idutilisateur }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Mot de passe</label>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                            ••••••••••••
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Téléphone</label>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                            {{ Auth::user()->telephone ?? 'Non renseigné' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Localisation</label>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                            {{ Auth::user()->ville->nomville ?? 'Non renseigné' }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Mes annonces en ligne</h3>
                
                <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-lbc-orange">
                        <i class="fa-solid fa-box-open text-2xl"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Vous n'avez aucune annonce en ligne.</p>
                    <a href="#" class="mt-4 inline-block bg-lbc-orange text-white px-6 py-2 rounded-lg font-bold hover:bg-lbc-orange_hover transition shadow-sm text-sm">
                        <i class="fa-solid fa-plus mr-2"></i> Déposer une annonce
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection