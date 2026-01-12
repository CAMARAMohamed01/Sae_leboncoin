@extends('layouts.app')

@section('title', 'Inscription - Email')

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)] bg-gray-50 pt-12 pb-12">
    <div class="bg-white p-8 w-full max-w-md rounded-xl shadow-sm border border-gray-200">
        
        <h3 class="text-2xl font-extrabold text-[#1f2d3d] mb-6 font-sans text-center">
            Commençons par un e-mail
        </h3>

        {{-- Le formulaire pointe vers la route définie dans web.php --}}
        <form action="{{ route('inscription.perso.email.post') }}" method="POST" class="space-y-6">
            @csrf

            <div class="form-group">
                <label for="email" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    E-mail <span class="text-lbc-orange">*</span>
                    <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                Choisissez une adresse mail à laquelle vous avez accès et qui n'est pas encore utilisée pour ce site
                            </div>
                        </div>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    autofocus
                    placeholder="exemple@email.com"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors duration-200 @error('email') border-red-500 @enderror"
                >
                {{-- Affichage des erreurs de validation (ex: Email déjà pris) --}}
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-semibold flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input 
                        id="newsletter" 
                        name="newsletter" 
                        type="checkbox" 
                        {{ old('newsletter') ? 'checked' : '' }}
                        class="w-5 h-5 border border-gray-300 rounded text-lbc-orange focus:ring-lbc-orange focus:ring-opacity-50 cursor-pointer"
                    >
                </div>
                <div class="ml-3 text-sm">
                    <label for="newsletter" class="font-medium text-gray-600 cursor-pointer select-none">
                        Recevoir les bons plans de nos sites partenaires
                    </label>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-2xl transition duration-200 shadow-md flex justify-center items-center gap-2 transform active:scale-[0.98]"
            >
                Suivant
                <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>

        </form>

        <p class="mt-6 text-xs text-gray-400 text-center">
            * Champ obligatoire
        </p>

    </div>
</div>
@endsection