@extends('layouts.app')

@section('title', 'Déposer une annonce')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        
        <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-8 font-sans">
            Déposer une annonce
        </h1>
        

        {{-- Affichage des erreurs de validation globales --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-100">
                <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('annonces.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Détails du bien</h2>
                <p class="text-sm text-gray-500 mb-8 italic">
            Les champs marqués d'un <span class="text-lbc-orange font-bold">*</span> sont obligatoires.
        </p>
                <div class="form-group mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Titre de l'annonce <span class="text-red-500">*</span><div class="group relative inline-block ml-2 align-middle">
            <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                Soyez précis : Type de bien + Atout majeur + Quartier.
            </div>
        </div></label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors" 
                           placeholder="Ex: Charmant studio centre-ville avec balcon">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Type d'hébergement <span class="text-red-500">*</span></label>
                        <select name="type_hebergement" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange bg-white">
                            @foreach($typesHebergement as $type)
                                <option value="{{ $type->idtypehebergement }}" {{ old('type_hebergement') == $type->idtypehebergement ? 'selected' : '' }}>
                                    {{ $type->typehebergement }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Prix par nuit (€) <span class="text-red-500">*</span><div class="group relative inline-block ml-2 align-middle">
        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
        <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
            Prix net vendeur. N'incluez pas les frais de ménage ici.
        </div>
    </div></label>
                        <input type="number" name="prix" value="{{ old('prix') }}" required min="1" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Capacité (Voyageurs) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacite" value="{{ old('capacite', 2) }}" required min="1" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre de chambres <span class="text-red-500">*</span></label>
                        <input type="number" name="chambres" value="{{ old('chambres', 1) }}" required min="0" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description <span class="text-red-500">*</span><div class="group relative inline-block ml-2 align-middle">
        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
        <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
            Mentionnez la proximité des commerces et l'ambiance du quartier. Soyez honnête !
        </div>
    </div></label>
                    <textarea name="description" rows="5" required 
                              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors" 
                              placeholder="Décrivez votre bien en détail (équipements, ambiance, proximité des transports...)...">{{ old('description') }}</textarea>
                </div>
            </section>

            <hr class="border-gray-200">

            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Localisation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">N°</label>
                        <input type="number" name="adresse_numero" value="{{ old('adresse_numero') }}" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rue / Voie <span class="text-red-500">*</span><div class="group relative inline-block ml-2 align-middle">
        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
        <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
            Votre adresse exacte ne sera communiquée au voyageur qu'une fois la réservation confirmée.
        </div>
    </div></label>
                        <input type="text" name="adresse_rue" value="{{ old('adresse_rue') }}" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Code Postal <span class="text-red-500">*</span></label>
                        <input type="text" name="ville_cp" value="{{ old('ville_cp') }}" required maxlength="5" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ville <span class="text-red-500">*</span></label>
                        <input type="text" name="ville_nom" value="{{ old('ville_nom') }}" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                    </div>
                </div>
            </section>

            <hr class="border-gray-200">

            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Photos</h2>
                
                <div class="bg-gray-50 p-8 rounded-lg border-2 border-dashed border-gray-300 text-center hover:bg-gray-100 transition-colors">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fa-solid fa-images text-4xl text-lbc-orange mb-3"></i>
                        
                        <label class="block cursor-pointer">
                            <span class="sr-only">Choisir des photos</span>
                            <input type="file" name="photos[]" multiple required accept="image/png, image/jpeg, image/jpg"
                                   class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-6
                                   file:rounded-full file:border-0
                                   file:text-sm file:font-bold
                                   file:bg-lbc-orange file:text-white
                                   hover:file:bg-[#d64d0e]
                                   cursor-pointer"
                            />
                        </label>
                        
                        <div class="mt-4 text-sm text-gray-500 space-y-1">
                            <p>Ajoutez jusqu'à 10 photos pour mettre en valeur votre bien.</p>
                            <p class="text-xs text-gray-400">Formats : JPG, PNG (Max 2Mo par photo)</p>
                            <p class="text-xs font-semibold text-lbc-blue mt-2">
                                <i class="fa-regular fa-keyboard"></i> Maintenez la touche <strong>Ctrl</strong> (ou <strong>Cmd</strong>) pour sélectionner plusieurs images.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="w-full md:w-auto bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-4 px-10 rounded-xl shadow-md transition transform active:scale-[0.98] text-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Publier mon annonce
                </button>
            </div>

        </form>
    </div>
</div>
@endsection