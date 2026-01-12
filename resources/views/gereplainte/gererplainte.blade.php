@extends('layouts.app')

@section('title', 'Mes incidents')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans mb-8">Mes Plaintes Déclarées</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($plaintes->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-solid fa-triangle-exclamation text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune plainte déclarée</h3>
            <p class="text-gray-500 mb-4">Vous n'avez pas encore eu de problème signalé sur vos locations.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($plaintes as $plainte)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-bold text-gray-700">Incident #{{ $plainte->idincident }}</span>
                        <span class="{{ $plainte->statut_reglament == 'Terminé' ? 'bg-green-100 text-green-800' : ($plainte->statut_reglament == 'En litige' ? 'bg-red-100 text-red-800' : ($plainte->statut_reglament == 'En relance' ? 'bg-fuchsia-100 text-fuchsia-800' : ($plainte->statut_reglament == 'En justice' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'))) }} text-xs font-bold px-3 py-1 rounded-full">
                            {{ $plainte->statut_reglament }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $plainte->typeincident }}</h2>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $plainte->description }}</p>
                        </div>
                        
                        @if ($plainte->statut_reglament == 'En attente')
                            <div class="flex flex-col gap-2 items-end min-w-max">
                                <form action="{{ route('incidents.annuler', $plainte->idincident) }}" method="POST" class="w-full">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition w-full" onclick="return confirm('Confirmer l\'annulation ?')">
                                        Annuler sa plainte
                                    </button>
                                </form>
                            </div>
                        @elseif ($plainte->statut_reglament == 'En litige')
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Réponse du propriétaire :</p>
                                <p class="text-sm italic text-gray-700 line-clamp-3">"{{ $plainte->reponse_proprietaire }}"</p>
                            </div>
                        @elseif ($plainte->statut_reglament == 'En relance')
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Votre réponse</p>
                                <p class="text-sm italic text-gray-700 line-clamp-3">"{{ $plainte->reponse_locataire }}"</p>
                            </div>
                        @elseif ($plainte->statut_reglament == 'En justice')
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Motif</p>
                                <p class="text-sm italic text-gray-700 line-clamp-3">"{{ $plainte->reponse_locataire }}"</p>
                            </div>
                        @else
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase">Cloturé le</p>
                                <p class="font-semibold text-gray-700">{{ $plainte->datecloture }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($plainte->statut_reglament == 'En litige')
                        <div class="flex flex-row flex-wrap items-center justify-center gap-3 w-full mb-4 pt-4 border-t border-dashed border-gray-100">
                            
                            <form action="{{ route('incidents.annuler', $plainte->idincident) }}" method="POST" class="inline-block">
                                @csrf @method('PATCH')
                                <button type="submit" class="whitespace-nowrap px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition shadow-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette plainte ?')">
                                    Annuler la plainte
                                </button>
                            </form> 

                            <button type="button" onclick="openGlobalModal({{ $plainte->idincident }}, 'info')" class="whitespace-nowrap px-4 py-2 border border-gray-300 text-gray-700 bg-white text-sm font-semibold rounded hover:bg-gray-50 transition shadow-sm">
                                Demander des informations complémentaires
                            </button>

                            <button type="button" onclick="openGlobalModal({{ $plainte->idincident }}, 'refus')" class="whitespace-nowrap px-4 py-2 border border-red-200 text-red-700 bg-red-50 text-sm font-semibold rounded hover:bg-red-100 transition shadow-sm">
                                Refuser les explications
                            </button>
                        </div>
                    @endif

                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-end text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase">Déclaré le</p>
                            <p class="font-semibold text-gray-700">{{ $plainte->datedeclaration }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase">Concerne l'annonce</p>
                            <p class="font-bold text-blue-600 hover:underline">
                                @if($plainte->reservation && $plainte->reservation->annonce)
                                    <a href="{{ route('annonces.show', $plainte->reservation->annonce->idannonce) }}">{{ $plainte->reservation->annonce->titreannonce }}</a>
                                @else
                                    <span class="text-gray-500">Annonce (N/A)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="infoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center modal-backdrop">
    <div class="relative p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">Demande d'informations</h3>
            <p class="text-sm text-gray-500 mb-4">Précisez au propriétaire les éléments manquants ou les éclaircissements souhaités.</p>
            <form id="infoForm" action="" method="POST">
                @csrf
                <textarea name="message" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Votre message..." required></textarea>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeGlobalModal('info')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="refusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center modal-backdrop">
    <div class="relative p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">Refuser les explications</h3>
            <p class="text-sm text-gray-500 mb-4">Expliquez pourquoi vous refusez les explications du propriétaire. Un administrateur interviendra.</p>
            <form id="refusForm" action="" method="POST">
                @csrf
                <textarea name="motif_refus" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-red-500" placeholder="Motif du refus..." required></textarea>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeGlobalModal('refus')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openGlobalModal(id, type) {
        const modal = document.getElementById(type + 'Modal');
        const form = document.getElementById(type + 'Form');
        
        let routeTemplate = (type === 'info') 
            ? "{{ route('incidents.demander-info', ':id') }}" 
            : "{{ route('incidents.refus', ':id') }}";
        
        form.action = routeTemplate.replace(':id', id);
        modal.classList.remove('hidden');
    }

    function closeGlobalModal(type) {
        document.getElementById(type + 'Modal').classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            event.target.classList.add('hidden');
        }
    }
</script>
@endsection