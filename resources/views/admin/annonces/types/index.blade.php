@extends('layouts.app')

@section('title', 'Gestion Types Hébergement')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                <i class="fa-solid fa-hotel text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Types d'Hébergement</h1>
                <p class="text-gray-500 mt-1">Espace Service Annonce</p>
            </div>
        </div>
        <a href="{{ route('admin.annonces.types.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Type
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
                    <th class="px-6 py-4">Nom du Type</th>
                    <th class="px-6 py-4 text-center">Annonces associées</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($types as $type)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-gray-400">#{{ $type->idtypehebergement }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $type->typehebergement }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold border border-indigo-100">
                                {{ $type->annonces_count }} annonces
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection