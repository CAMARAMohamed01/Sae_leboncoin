@extends('layouts.app')

@section('title', 'Nouvel Équipement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        
        <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-8 font-sans border-b border-gray-100 pb-4">
            Ajouter un nouvel équipement
        </h1>

        <form action="{{ route('admin.annonces.equipements.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- SECTION 1 : Définition -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Détails</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nom de l'équipement <span class="text-red-500">*</span></label>
                        <input type="text" name="nomequipement" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Ex: Fibre Optique, Jacuzzi...">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Type / Catégorie <span class="text-red-500">*</span></label>
                        <select name="idtypeequipement" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white">
                            @foreach($types as $type)
                                <option value="{{ $type->idtypehebergement ?? $type->idtypeequipement }}">
                                    {{ $type->typehebergement ?? $type->typeequipement }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- SECTION 2 : Liaison (Optionnel) -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Lier à des annonces existantes (Optionnel)</h2>
                <p class="text-sm text-gray-500 mb-4">Sélectionnez les annonces qui possèdent déjà cet équipement.</p>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-64 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($annonces as $annonce)
                        <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg hover:border-blue-300 cursor-pointer transition">
                            <input type="checkbox" name="annonces[]" value="{{ $annonce->idannonce }}" class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div>
                                <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $annonce->titreannonce }}</p>
                                <p class="text-xs text-gray-500">{{ $annonce->ville->nomville ?? '' }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Enregistrer l'équipement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection