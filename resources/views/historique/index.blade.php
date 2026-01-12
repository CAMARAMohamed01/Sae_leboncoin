{{-- resources/views/historique/index.blade.php --}}

@extends('layouts.app') {{-- Adaptez à votre layout --}}

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Mon Historique de Recherches</h1>

    @if($recherches->isEmpty())
        <p class="text-gray-500">Vous n'avez encore sauvegardé aucune recherche.</p>
    @else
        <div class="space-y-4">
            @foreach ($recherches as $recherche)
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-blue-500">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        {{-- Colonne 1 : Infos principales --}}
                        <div>
                            <p class="text-xl font-semibold text-gray-800">
                                @if($recherche->ville)
                                    {{ $recherche->ville->nomville }}
                                @else
                                    <span class="text-gray-500">Localisation (ID: {{ $recherche->idville ?? 'N/A' }})</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Type : {{ optional($recherche->typeHebergement)->nomtype ?? 'Tous' }}
                            </p>
                        </div>
                        
                        {{-- Colonne 2 : Filtres avancés --}}
                        <div class="md:col-span-1">
                            <span class="text-sm font-medium text-blue-600">Filtres :</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @if ($recherche->prix_min) <span class="bg-gray-100 px-2 py-1 rounded text-xs">Prix Min: {{ $recherche->prix_min }}€</span> @endif
                                @if ($recherche->prix_max) <span class="bg-gray-100 px-2 py-1 rounded text-xs">Prix Max: {{ $recherche->prix_max }}€</span> @endif
                                @if ($recherche->nbchambre_min) <span class="bg-gray-100 px-2 py-1 rounded text-xs">Chambres ≥ {{ $recherche->nbchambre_min }}</span> @endif
                                @if ($recherche->animaux_acceptes) <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Animaux OK</span> @endif
                                @if ($recherche->fumeurs_autorises) <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Fumeurs OK</span> @endif
                            </div>
                        </div>

                        {{-- Colonne 3 : Action et date --}}
                        <div class="flex flex-col justify-between items-end">
                            <p class="text-xs text-gray-400 mb-2">
                                Enregistrée le {{ $recherche->created_at->format('d/m/Y à H:i') }}
                            </p>
                            
                            {{-- Bouton pour relancer la recherche --}}
                            <a href="{{ 
                                route('recherche.index', [ 
                                    // Utilise la relation si elle existe, sinon utilise les ID si vous les avez dans l'URL de recherche
                                    'localisation' => optional($recherche->ville)->nomville,
                                    
                                    'type_hebergement' => $recherche->idtypehebergement,
                                    'date_arrivee' => optional($recherche->date)->dateacte, 
                                    
                                    'prix_min' => $recherche->prix_min,
                                    'prix_max' => $recherche->prix_max,
                                    'chambres' => $recherche->nbchambre_min,
                                    
                                    // Les valeurs des checkboxes doivent être 1 ou null/absent pour la relance
                                    'animaux' => $recherche->animaux_acceptes ? 1 : null,
                                    'fumeur' => $recherche->fumeurs_autorises ? 1 : null,
                                ]) 
                            }}" 
                            class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-150">
                                Relancer la recherche
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recherches->links() }}
        </div>
    @endif
</div>
@endsection