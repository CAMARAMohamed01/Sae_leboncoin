@extends('layouts.app')

@section('content')
<div class="flex flex-col justify-center items-center min-h-[calc(100vh-200px)]">
    
    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-lg w-full max-w-[480px] border border-gray-100">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] mb-2">Bonjour !</h1>
            <p class="text-gray-500 text-sm">Connectez-vous pour découvrir toutes nos fonctionnalités.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            @if(request()->has('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            <div class="form-group">
                <label for="email" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                    Adresse e-mail
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="Ex: jean.dupont@mail.com"
                        class="w-full pl-4 pr-4 py-3 rounded-xl border @error('email') border-red-500 bg-red-50 @else border-gray-300 bg-white @enderror text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-all duration-200 placeholder-gray-400"
                    >
                    @error('email')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        </div>
                    @enderror
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 font-medium pl-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <div class="flex justify-between items-end mb-2">
                    <label for="password" class="block text-sm font-bold text-[#1f2d3d]">
                        Mot de passe
                        <div class="group relative inline-block ml-2 align-middle">
                        <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                            <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                                8 caractères minimum<br>Au moins 1 majuscule<br>Au moins 1 minuscule<br>Au moins 1 chiffre
                            </div>
                        </div>
                    </label>
                </div>
                
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required
                    placeholder="Votre mot de passe"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-all duration-200 placeholder-gray-400"
                >
                
                <div class="flex justify-end mt-2">
                    <a href="#" class="text-xs font-semibold text-lbc-blue hover:underline decoration-2 underline-offset-2">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <button type="submit" class="w-full bg-lbc-orange hover:bg-lbc-orange_hover text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 shadow-md hover:shadow-lg transform active:scale-[0.98] flex justify-center items-center gap-3 text-base">
                Se connecter
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-600 mb-4">Envie de nous rejoindre ?</p>
            <a href="/inscription" class="block w-full border-2 border-lbc-blue text-lbc-blue hover:bg-lbc-blue hover:text-white font-bold py-3 px-4 rounded-xl transition duration-200 text-center">
                Créer un compte
            </a>
        </div>

    </div>
    
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-400">
            <i class="fa-solid fa-lock mr-1"></i> Vos données personnelles sont protégées.
        </p>
    </div>
    <div
      id="g_id_onload"
      data-auto_prompt="false"
      data-callback="handleCredentialResponse"
      data-client_id="PUT_YOUR_WEB_CLIENT_ID_HERE"
    ></div>
    <!-- g_id_signin places the button on a page and supports customization -->
    <div class="g_id_signin"></div>
</div>
@endsection