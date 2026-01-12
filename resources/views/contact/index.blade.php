@extends('layouts.app')



@section('content')

    <div class="bg-gray-50 min-h-screen font-sans">

       

        {{-- Header simple --}}

        <div class="bg-white border-b border-gray-200 py-8 px-4 sm:px-6 lg:px-8">

            <div class="max-w-7xl mx-auto">

                <nav class="flex text-sm text-gray-500 mb-4">

                    <a href="/" class="hover:text-orange-600">Accueil</a>

                    <span class="mx-2">/</span>

                    <a href="{{ route('aide.index') }}" class="hover:text-orange-600">Centre d'aide</a>

                    <span class="mx-2">/</span>

                    <span class="text-gray-900 font-medium">Contact</span>

                </nav>

                <h1 class="text-3xl font-bold text-gray-900">Contactez-nous</h1>

            </div>

        </div>



        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

               

                {{-- Colonne Gauche : Le Formulaire --}}

                <div class="lg:col-span-2">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">

                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Envoyer une demande</h2>

                       

                        {{-- Affiche le message de succès si tout s'est bien passé --}}
@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Succès !</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

{{-- Affiche les erreurs de validation globales --}}
@if ($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <ul>
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('contact.send') }}" method="POST" enctype="multipart/form-data">
    @csrf 

    {{-- Sujet --}}
    <div class="mb-6">
        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Quel est le sujet de votre demande ?</label>
        <select id="subject" name="subject" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md border shadow-sm">
            <option>Je n'arrive pas à me connecter</option>
            <option>Problème avec une transaction</option>
            <option>Signaler une fraude</option>
            <option>Gestion de mes annonces</option>
            <option>Autre question</option>
        </select>
    </div>

    {{-- Email --}}
    <div class="mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Votre adresse email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 border px-4" placeholder="exemple@email.com" required>
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    {{-- Message --}}
    <div class="mb-6">
        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Description détaillée</label>
        <div class="mt-1">
            <textarea id="message" name="message" rows="6" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md p-4" placeholder="Détails..." required>{{ old('message') }}</textarea>
        </div>
        @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    {{-- Pièce jointe --}}
    <div class="mb-8">
        <label class="block text-sm font-medium text-gray-700 mb-2">Pièces jointes (optionnel)</label>
        <input id="file-upload" name="file-upload" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
        @error('file-upload') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors w-full sm:w-auto">
            Envoyer le message
        </button>
    </div>
</form>

                    </div>

                </div>



                {{-- Colonne Droite : Sidebar Info --}}

                <div class="lg:col-span-1">

                    {{-- FAQ Rapide --}}

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Questions fréquentes</h3>

                        <ul class="space-y-4">

                            <li>

                                <a href="{{ route('aide.index') }}" class="text-sm text-blue-600 hover:underline flex items-start">

                                    <svg class="h-5 w-5 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>

                                    Comment modifier mon annonce ?

                                </a>

                            </li>

                            <li>

                                <a href="{{ route('aide.index') }}" class="text-sm text-blue-600 hover:underline flex items-start">

                                    <svg class="h-5 w-5 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                    Réinitialiser mon mot de passe
                                </a>

                            </li>

                            <li>

                                <a href="{{ route('aide.index') }}" class="text-sm text-blue-600 hover:underline flex items-start">

                                    <svg class="h-5 w-5 mr-2 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>

                                    Paiement sécurisé : comment ça marche ?

                                </a>

                            </li>

                        </ul>

                    </div>



                    {{-- Info Délai --}}

                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">

                        <div class="flex">

                            <div class="flex-shrink-0">

                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">

                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>

                                </svg>

                            </div>

                            <div class="ml-3">

                                <h3 class="text-sm font-medium text-blue-800">Délai de réponse</h3>

                                <div class="mt-2 text-sm text-blue-700">

                                    <p>Nos équipes sont actuellement très sollicitées. Le délai moyen de réponse est de <strong>24 à 48 heures</strong>.</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



            </div>

        </div>

    </div>

@endsection