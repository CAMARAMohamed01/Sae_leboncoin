@extends('layouts.app')

@section('title', 'Vérification Email')

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)] bg-gray-50 pt-12 pb-12">
    <div class="bg-white p-8 w-full max-w-md rounded-xl shadow-sm border border-gray-200 text-center">
        
        <h3 class="text-2xl font-extrabold text-[#1f2d3d] mb-4 font-sans">
            Vérifiez votre e-mail
        </h3>
        
        <p class="text-gray-600 mb-6 text-sm">
            Nous avons envoyé un code à 6 chiffres à <strong>{{ $email }}</strong>.<br>
            Saisissez-le ci-dessous pour continuer.
        </p>

        <form action="{{ route('inscription.perso.verify.check') }}" method="POST" class="space-y-6">
            @csrf

            <div class="form-group">
                <input 
                    type="text" 
                    name="code" 
                    required 
                    autofocus
                    placeholder="123456"
                    maxlength="6"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 text-center text-2xl tracking-widest font-bold focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors duration-200 @error('code') border-red-500 @enderror"
                >
                @error('code')
                    <p class="text-red-500 text-xs mt-2 font-semibold flex items-center justify-center">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-3 px-4 rounded-2xl transition duration-200 shadow-md transform active:scale-[0.98]"
            >
                Vérifier
            </button>
        </form>

        <div class="mt-6 text-xs text-gray-400">
            <a href="{{ route('inscription.perso.email') }}" class="text-lbc-blue hover:underline">Modifier l'email</a>
            <span class="mx-2">•</span>
            <a href="#" class="text-lbc-blue hover:underline">Renvoyer le code</a>
        </div>

    </div>
</div>
@endsection