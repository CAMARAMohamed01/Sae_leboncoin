@extends('layouts.app')

@section('title', 'Nouveau Type Hébergement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        
        <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-8 font-sans border-b border-gray-100 pb-4">
            Créer un nouveau type d'hébergement
        </h1>

        <form action="{{ route('admin.annonces.types.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- SECTION 1 : Définition -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Détails</h2>
                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nom du type <span class="text-red-500">*</span></label>
                    <input type="text" name="typehebergement" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Ex: Cabane dans les arbres, Loft Industriel...">
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- SECTION 2 : Liaison (Optionnel) -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Réassigner des annonces (Optionnel)</h2>
                <p class="text-sm text-gray-500 mb-4 bg-yellow-50 text-yellow-800 p-3 rounded-lg border border-yellow-200">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> 
                    Attention : Les annonces sélectionnées quitteront leur catégorie actuelle pour rejoindre ce nouveau type.
                </p>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-80 overflow-y-auto grid grid-cols-1 gap-2">
                    @foreach($annonces as $annonce)
                        <label class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-indigo-300 cursor-pointer transition">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="annonces[]" value="{{ $annonce->idannonce }}" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $annonce->titreannonce }}</p>
                                    <p class="text-xs text-gray-500">{{ $annonce->ville->nomville ?? '' }}</p>
                                </div>
                            </div>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                Actuel : {{ $annonce->typeHebergement->typehebergement ?? 'Aucun' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Enregistrer le type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection