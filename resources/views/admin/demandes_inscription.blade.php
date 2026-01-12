@extends('layouts.app')

@section('title', 'Gestion des Inscriptions')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Demandes d'inscription</h1>
        <p class="text-gray-500 mt-2">
            Liste des utilisateurs ayant soumis une pièce d'identité pour validation.
        </p>
    </div>

    @if($utilisateurs->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-solid fa-check-double text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tout est à jour !</h3>
            <p class="text-gray-500">Aucune demande d'inscription en attente pour le moment.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Utilisateur</th>
                            <th class="px-6 py-4">Email / Tél</th>
                            <th class="px-6 py-4">Type Pièce</th>
                            <th class="px-6 py-4">Document</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($utilisateurs as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-base">
                                        {{ $user->nom_affichage }}
                                    </div>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                        {{ $user->particulier ? 'Particulier' : 'Professionnel' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span><i class="fa-regular fa-envelope mr-1"></i> {{ $user->emailutilisateur }}</span>
                                        <span class="mt-1"><i class="fa-solid fa-phone mr-1"></i> {{ $user->telutilisateur }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                        {{ $user->identite->typeidentite ?? 'N/A' }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        N° {{ $user->identite->numeroidentite ?? 'Inconnu' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->identite && $user->identite->lien_document)
                                        <a href="{{ asset($user->identite->lien_document) }}" target="_blank" class="flex items-center gap-2 text-lbc-blue hover:underline font-semibold">
                                            <i class="fa-regular fa-file-pdf text-xl"></i>
                                            Voir le document
                                        </a>
                                    @else
                                        <span class="text-red-400 italic">Fichier manquant</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- Ici on pourrait imaginer des boutons pour Valider ou Refuser l'identité --}}
                                        <button class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                            <i class="fa-solid fa-check"></i> Valider
                                        </button>
                                        <button class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                            <i class="fa-solid fa-xmark"></i> Refuser
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection