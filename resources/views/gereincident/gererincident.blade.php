@extends('layouts.app')

@section('title', 'Mes incidents')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans mb-8">Mes Incidents Déclarés</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($incidents->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-solid fa-triangle-exclamation text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun incident déclaré</h3>
            <p class="text-gray-500 mb-4">Vous n'avez pas encore eu de problème signalé sur vos locations.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($incidents as $incident)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-bold text-gray-700">Incident #{{ $incident->idincident }}</span>
                        <span class="{{ $incident->statut_reglament == 'Terminé' ? 'bg-green-100 text-green-800' : ($incident->statut_reglament == 'En litige' ? 'bg-red-100 text-red-800' : ($incident->statut_reglament == 'En relance' ? 'bg-fuchsia-100 text-fuchsia-800' : ($incident->statut_reglament == 'En justice' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'))) }} text-xs font-bold px-3 py-1 rounded-full">
                            {{ $incident->statut_reglament }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $incident->typeincident }}</h2>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $incident->description }}</p>
                        </div>
                        
                        @if ($incident->statut_reglament == 'En attente')
                            <div class="flex flex-col gap-2 items-end min-w-max">
                                
                                <form action="{{ route('incidents.reconnaitre', $incident->idincident) }}" method="POST" class="w-full">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition w-full" onclick="return confirm('Confirmer la reconnaissance ?')">
                                        Je reconnais l'incident
                                    </button>
                                </form>

                                <button type="button" onclick="openContestModal({{ $incident->idincident }})" class="px-4 py-2 border border-red-200 text-red-700 bg-red-50 text-sm font-semibold rounded hover:bg-red-100 transition w-full">
                                    Je ne suis pas responsable
                                </button>
                            </div>
                        
                        @elseif ($incident->statut_reglament == 'En litige')
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Votre réponse :</p>
                                <p class="text-sm italic text-gray-700">"{{ $incident->reponse_proprietaire }}"</p>
                            </div>
                        @elseif ($incident->statut_reglament == 'En relance')
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Réponse du locataire :</p>
                                <p class="text-sm italic text-gray-700">"{{ $incident->reponse_locataire }}"</p>
                            </div>
                        @elseif ($incident->statut_reglament == 'En justice')
                            <div class="bg-gray-50 p-3 rounded border border-gray-100 max-w-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Motif</p>
                                <p class="text-sm italic text-gray-700 line-clamp-3">"{{ $incident->reponse_locataire }}"</p>
                            </div>
                        @else
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase">Cloturé le</p>
                                <p class="font-semibold text-gray-700">{{ $incident->datecloture }}</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        @if ($incident->statut_reglament == 'En relance')
                            <div class="flex flex-row gap-2 items-center justify-center mb-2">                                
                                <form action="{{ route('incidents.reconnaitre', $incident->idincident) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700 transition" onclick="return confirm('Confirmer la reconnaissance ?')">
                                        Je reconnais l'incident
                                    </button>
                                </form>

                                <button type="button" onclick="openContestModal({{ $incident->idincident }})" class="px-4 py-2 border border-red-200 text-red-700 bg-red-50 text-sm font-semibold rounded hover:bg-red-100 transition">
                                    Je ne suis pas responsable
                                </button>                                
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-end text-sm">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase">Déclaré le</p>
                            <p class="font-semibold text-gray-700">{{ $incident->datedeclaration }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase">Concerne l'annonce</p>
                            <p class="font-bold text-lbc-blue hover:underline">
                                @if($incident->reservation && $incident->reservation->annonce)
                                    <a href="{{ route('annonces.show', $incident->reservation->annonce->idannonce) }}">{{ $incident->reservation->annonce->titreannonce }}</a>
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


<div id="contestModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">Contester la responsabilité</h3>
            <p class="text-sm text-gray-500 mb-4">
                Veuillez expliquer pourquoi vous n'êtes pas responsable de cet incident. Ce message sera transmis à l'administrateur.
            </p>
            
            <form id="contestForm" action="" method="POST">
                @csrf
                <textarea name="motif_refus" rows="5" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Écrivez votre explication ici..." maxlength="1000" required></textarea>
              
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeContestModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Envoyer la contestation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openContestModal(idIncident) {
        const modal = document.getElementById('contestModal');
        const form = document.getElementById('contestForm');

        let url = "{{ route('incidents.contester', ':id') }}";
        url = url.replace(':id', idIncident);
        
        form.action = url;

        modal.classList.remove('hidden');
    }

    function closeContestModal() {
        document.getElementById('contestModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('contestModal');
        if (event.target == modal) {
            closeContestModal();
        }
    }
</script>

@endsection