@extends('layouts.app')

@section('title', 'Signaler un incident')

@section('content')
<div class="min-h-screen bg-[#f4f6f7] py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- En-tête de la carte avec un design plus épuré --}}
        <div class="bg-white rounded-t-2xl shadow-sm border border-gray-200 p-8 border-b-0 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-orange-500"></div>
            
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2 flex items-center gap-3">
                        <span class="bg-red-50 text-red-500 p-2 rounded-lg">
                            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        </span>
                        Signaler un incident
                    </h1>
                    <p class="text-gray-500 text-sm ml-14">
                        Nous sommes désolés que vous rencontriez un problème.
                    </p>
                </div>
            </div>

            <div class="mt-6 ml-14 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                <div class="h-12 w-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-home text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Concerne la location</p>
                    <p class="text-lg font-bold text-gray-800 leading-tight">
                        {{ $annonce->titreannonce ?? 'Annonce introuvable' }}
                    </p>
                    @if ($idReservation)
                        <input type="hidden" name="idReservation" value="{{ $idReservation }}">
                    @endif
                </div>
            </div>
        </div>

        {{-- Corps du formulaire --}}
        <div class="bg-white rounded-b-2xl shadow-lg shadow-gray-200/50 border border-t-0 border-gray-200 p-8 pt-0">
            
            <form action="{{ url('/formsincidents/save') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Champs cachés --}}
                @if ($idReservation)
                    <input type="hidden" name="idReservation" value="{{ $idReservation }}">
                @else
                    {{-- Si aucune ID de réservation n'est trouvée, vous devriez afficher une erreur ou rediriger --}}
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                      <strong class="font-bold">Erreur !</strong>
                      <span class="block sm:inline">Aucune réservation valide trouvée pour cette annonce.</span>
                    </div>
                @endif
                <input type="hidden" name="remboursementvalide" value="False">

                {{-- Message d'information stylisé --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 flex gap-4 items-start">
                    <i class="fa-solid fa-circle-info text-blue-500 text-xl mt-0.5"></i>
                    <div class="text-sm text-blue-800 leading-relaxed">
                        <strong class="block mb-1 font-bold">Important</strong>
                        Merci de décrire l'incident avec précision. Cette déclaration sera transmise au propriétaire et à notre service de médiation pour une résolution rapide.
                    </div>
                </div>

                <div class="grid gap-8">
                    {{-- Type d'incident --}}
                    <div class="group">
                        <label for="typeincident" class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-lbc-orange transition-colors">
                            Quel est le type de problème ? <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="typeincident" id="typeincident" required 
                                    class="block w-full pl-5 pr-12 py-4 text-base border-gray-200 bg-gray-50 focus:bg-white text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-lbc-orange/20 focus:border-lbc-orange transition-all duration-200 cursor-pointer shadow-sm hover:border-gray-300 appearance-none font-medium">
                                <option value="" disabled selected>Sélectionnez une catégorie...</option>
                                <option value="panne">⚡ Panne (Électroménager, électricité...)</option>
                                <option value="casse">🔨 Casse / Dégradation</option>
                                <option value="proprete">🧹 Propreté / Hygiène</option>
                                <option value="autre">❓ Autre problème</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                        @error('typeincident')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-xmark"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="group">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-lbc-orange transition-colors">
                            Détails de l'incident <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="description" name="description" rows="6" required
                                      class="block w-full p-5 text-base border-gray-200 bg-gray-50 focus:bg-white text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-lbc-orange/20 focus:border-lbc-orange transition-all duration-200 shadow-sm hover:border-gray-300 resize-y"
                                      placeholder="Expliquez ce qu'il s'est passé, les circonstances, l'heure de constatation..."></textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400 bg-white/80 px-2 py-1 rounded">
                                Soyez précis
                            </div>
                        </div>
                        @error('description')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-xmark"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Zone de boutons --}}
                <div class="pt-6 flex flex-col-reverse sm:flex-row items-center justify-between gap-4 border-t border-gray-100">
                    <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-800 text-sm font-semibold py-3 px-4 rounded-lg hover:bg-gray-100 transition-colors w-full sm:w-auto text-center">
                        Annuler
                    </a>
                    
                    <button type="submit" 
                            class="w-full sm:w-auto flex items-center justify-center py-3.5 px-8 border border-transparent rounded-xl shadow-lg shadow-orange-500/20 text-base font-bold text-white bg-gradient-to-r from-[#ec5a13] to-[#ff7d40] hover:to-[#ec5a13] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ec5a13] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fa-solid fa-paper-plane mr-2.5"></i> Envoyer la déclaration
                    </button>
                </div>

            </form>
        </div>
        
        <p class="text-center text-gray-400 text-xs mt-8">
            &copy; {{ date('Y') }} Leboncoin - Service Qualité
        </p>
    </div>
</div>
@endsection