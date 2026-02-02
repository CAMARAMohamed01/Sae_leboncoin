@extends('layouts.app')

@section('title', 'Service Immobilier')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
            <i class="fa-solid fa-house-chimney-medical text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Espace Expertise</h1>
            <p class="text-gray-500 mt-1">Validez la qualité des biens en attente (Avis Expert).</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('warning'))
        <div class="mb-6 bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-solid fa-clipboard-check text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune annonce en attente</h3>
            <p class="text-gray-500">Toutes les annonces ont été expertisées.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($annonces as $annonce)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
                    
                    {{-- Photo --}}
                    <div class="w-full md:w-64 h-48 md:h-auto bg-gray-200 relative flex-shrink-0">
                        @if($annonce->photos->isNotEmpty())
                            <img src="{{ asset($annonce->photos->first()->lienurl) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100"><i class="fa-solid fa-image text-3xl"></i></div>
                        @endif
                        <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded">
                            Réf: #{{ $annonce->idannonce }}
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-1">
                                    {{ $annonce->titreannonce }}
                                </h3>
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">
                                    {{ $annonce->typeHebergement->typehebergement ?? 'Logement' }}
                                </span>
                            </div>
                            
                            <p class="text-gray-500 text-sm mb-4">
                                <i class="fa-solid fa-location-dot"></i> {{ $annonce->ville->nomville ?? '?' }}
                                <span class="mx-2">•</span>
                                Vendeur : <strong>{{ $annonce->proprietaire->nom_affichage ?? 'Inconnu' }}</strong>
                            </p>
                            
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 mb-4">
                                <p class="text-gray-600 text-sm line-clamp-2 italic">
                                    "{{ $annonce->descriptionannonce }}"
                                </p>
                            </div>
                        </div>

                        
                        <div class="mt-2 pt-4 border-t border-gray-100">
                            
                            <div id="actions-initial-{{ $annonce->idannonce }}" class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <a href="{{ route('annonces.show', $annonce->idannonce) }}" target="_blank" class="text-gray-600 hover:text-lbc-blue text-sm font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-eye"></i> Inspecter l'annonce
                                </a>

                                <div class="flex gap-3 w-full sm:w-auto">
                                    <button type="button" onclick="showNegativeForm({{ $annonce->idannonce }})" class="flex-1 sm:flex-none bg-white border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-bold transition flex justify-center items-center gap-2">
                                        <i class="fa-solid fa-thumbs-down"></i> Avis Négatif
                                    </button>

                                    <form action="{{ route('admin.immobilier.avis', $annonce->idannonce) }}" method="POST" class="flex-1 sm:flex-none" onsubmit="return confirm('Confirmer l\'avis POSITIF pour cette annonce ?');">
                                        @csrf
                                        <input type="hidden" name="avis" value="Positif">
                                        <button type="submit" class="w-full bg-purple-600 text-white hover:bg-purple-700 px-4 py-2.5 rounded-lg text-sm font-bold transition flex justify-center items-center gap-2 shadow-sm">
                                            <i class="fa-solid fa-thumbs-up"></i> Avis Positif
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div id="form-negatif-{{ $annonce->idannonce }}" class="hidden bg-red-50 p-4 rounded-lg border border-red-100 mt-2">
                                <form action="{{ route('admin.immobilier.avis', $annonce->idannonce) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="avis" value="Négatif">
                                    
                                    <label class="block text-xs font-bold text-red-700 mb-2 uppercase">Motif du refus (Obligatoire)</label>
                                    <textarea name="commentaire_expert" rows="3" required class="w-full text-sm p-3 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 mb-3" placeholder="Expliquez pourquoi cette annonce ne respecte pas les critères de qualité..."></textarea>
                                    
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="hideNegativeForm({{ $annonce->idannonce }})" class="text-gray-500 hover:text-gray-700 text-sm font-semibold px-3 py-2">
                                            Annuler
                                        </button>
                                        <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition">
                                            Confirmer le refus
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function showNegativeForm(id) {
        document.getElementById('actions-initial-' + id).classList.add('hidden');
        document.getElementById('form-negatif-' + id).classList.remove('hidden');
    }

    function hideNegativeForm(id) {
        document.getElementById('form-negatif-' + id).classList.add('hidden');
        document.getElementById('actions-initial-' + id).classList.remove('hidden');
    }
</script>

@endsection