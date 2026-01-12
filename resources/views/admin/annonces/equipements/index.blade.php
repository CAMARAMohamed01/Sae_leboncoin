@extends('layouts.app')

@section('title', 'Gestion Équipements')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                <i class="fa-solid fa-list-check text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Catalogue Équipements</h1>
                <p class="text-gray-500 mt-1">Espace Service Annonce</p>
            </div>
        </div>
        <a href="{{ route('admin.annonces.equipements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Créer un équipement
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nom de l'équipement</th>
                    <th class="px-6 py-4">Catégorie (Type)</th>
                    <th class="px-6 py-4 text-center">Annonces liées</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($equipements as $eq)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-gray-400">#{{ $eq->idequipement }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $eq->nomequipement }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                {{ $eq->type->typeequipement ?? 'Inconnu' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">
                                {{ $eq->annonces()->count() }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection