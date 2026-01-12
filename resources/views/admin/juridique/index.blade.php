@extends('layouts.app')

@section('title', 'Conformité Cookies')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-orange-600 text-white rounded-xl">
            <i class="fa-solid fa-scale-balanced text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Conformité RGPD</h1>
            <p class="text-gray-500 mt-1">Audit du consentement des cookies.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Carte Total -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-sm font-bold text-gray-500 uppercase">Interactions Totales</p>
            <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $total }}</p>
            <p class="text-xs text-gray-400 mt-1">Clics enregistrés</p>
        </div>

        <!-- Carte Acceptation -->
        <div class="bg-green-50 p-6 rounded-xl border border-green-100">
            <p class="text-sm font-bold text-green-600 uppercase">Taux d'acceptation</p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="text-3xl font-extrabold text-green-800">{{ $percentAccepted }}%</p>
                <span class="text-sm text-green-700 font-medium">({{ $accepted }})</span>
            </div>
        </div>

        <!-- Carte Refus -->
        <div class="bg-red-50 p-6 rounded-xl border border-red-100">
            <p class="text-sm font-bold text-red-600 uppercase">Taux de refus</p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="text-3xl font-extrabold text-red-800">{{ $percentRefused }}%</p>
                <span class="text-sm text-red-700 font-medium">({{ $refused }})</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Bloc Statut Légal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 h-full">
            <h2 class="text-lg font-bold text-gray-900 mb-4">État de la conformité</h2>
            
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                    <i class="fa-solid fa-check text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Module de consentement actif</h3>
                    <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                        Les choix des utilisateurs sont enregistrés de manière anonyme dans la table <code>cookiestats</code>. 
                        Cela permet de prouver le respect de la directive ePrivacy sans collecter de données personnelles (pas d'IP).
                    </p>
                    <div class="mt-4 flex gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            <i class="fa-solid fa-info-circle mr-1"></i> Directive ePrivacy
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                            Anonymisation totale
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloc Derniers Clics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-0 overflow-hidden h-full">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-900">Dernières interactions</h3>
            </div>
            <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
                @forelse($lastMonthStats as $stat)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="text-sm font-bold text-gray-800">
                                @if($stat->choix == 'accepted') 
                                    <span class="text-green-600">Accepté</span>
                                @else 
                                    <span class="text-red-600">Refusé</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">ID Stat: #{{ $stat->idstat }}</p>
                        </div>
                        <span class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($stat->date_action)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">Aucune donnée récente.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection