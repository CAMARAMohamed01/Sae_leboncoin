@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            {{-- Icone de succès --}}
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Message envoyé !
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Merci de nous avoir contactés. Votre demande a bien été transmise à notre équipe.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
                <p class="mb-6 text-gray-700">Vous allez recevoir un accusé de réception par email.</p>
                
                <a href="/" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    Retourner à l'accueil
                </a>
            </div>
        </div>
    </div>
@endsection