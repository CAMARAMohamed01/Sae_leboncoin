@extends('layouts.app')

@section('title', 'Demandes de suppression (DPO)')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center gap-4">
        <div class="p-3 bg-orange-100 text-red-600 rounded-xl">
            <i class="fa-solid fa-user-xmark text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Demandes de suppression</h1>
            <p class="text-gray-500 mt-1">Utilisateurs ayant exercé leur droit à l'oubli.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($demandes->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-regular fa-folder-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune demande en attente</h3>
            <p class="text-gray-500">Tous les droits à l'oubli ont été traités.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-800 font-bold uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Date demande</th>
                        <th class="px-6 py-4">Utilisateur</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($demandes as $demande)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $demande->utilisateur->nom_affichage ?? 'Inconnu' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $demande->utilisateur->emailutilisateur ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.rgpd.valider', $demande->id_demande) }}" method="POST" onsubmit="return confirm('Attention : Cette action va anonymiser définitivement cet utilisateur. Continuer ?');">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition flex items-center gap-2 ml-auto">
                                        <i class="fa-solid fa-eraser"></i> Exécuter l'oubli
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection