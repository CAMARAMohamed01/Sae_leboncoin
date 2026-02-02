@extends('layouts.app')

@section('title', 'Gestion des demandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Gestion des demandes</h1>
            <p class="text-gray-500 mt-1">Gérez les réservations et répondez aux voyageurs.</p>
        </div>
        <a href="{{ route('annonces.mes_annonces') }}" class="text-lbc-blue font-bold hover:underline text-sm">
            Voir mes annonces <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
    @endif

    @if($demandes->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-regular fa-calendar-xmark text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune demande reçue</h3>
            <p class="text-gray-500">Vos annonces n'ont pas encore reçu de demandes de réservation.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($demandes as $resa)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
                    
                    {{-- Colonne Infos Annonce --}}
                    <div class="w-full md:w-64 bg-gray-50 p-6 flex flex-col justify-center text-center md:text-left border-b md:border-b-0 md:border-r border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase mb-2">Demande pour</span>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-2">
                            <a href="{{ route('annonces.show', $resa->annonce->idannonce) }}" class="hover:underline">
                                {{ $resa->annonce->titreannonce }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fa-solid fa-location-dot"></i> {{ $resa->annonce->ville->nomville ?? '' }}
                        </p>
                        @if($resa->reglement)
                            <div class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200 inline-block text-center">
                                + {{ $resa->reglement->montant }} € (En attente)
                            </div>
                        @endif
                    </div>

                    {{-- Colonne Détails Réservation --}}
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-900">
                                            {{ $resa->locataire->nom_affichage ?? 'Utilisateur' }}
                                        </span>
                                        <span class="text-sm text-gray-500">souhaite réserver</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 w-fit">
                                        <i class="fa-regular fa-calendar text-blue-500"></i>
                                        <span>Du <strong>{{ $resa->dateDebut->dateacte }}</strong> au <strong>{{ $resa->dateFin->dateacte }}</strong></span>
                                    </div>
                                </div>

                                {{-- Statut (Badge) --}}
                                <div>
                                    @if($resa->statut_reservation === 'En attente')
                                        <span class="bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full border border-orange-200">
                                            <i class="fa-regular fa-clock"></i> En attente
                                        </span>
                                    @elseif($resa->statut_reservation === 'Acceptée')
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-200">
                                            <i class="fa-solid fa-check"></i> Acceptée
                                        </span>
                                    @elseif($resa->statut_reservation === 'Refusée')
                                        <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full border border-red-200">
                                            <i class="fa-solid fa-xmark"></i> Refusée
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Détails Voyageurs --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-user text-gray-400"></i>
                                    <span class="font-bold text-gray-900">{{ $resa->nbadulte }}</span> Adulte(s)
                                </div>
                                @if($resa->nbenfant > 0)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-child text-gray-400"></i>
                                    <span class="font-bold text-gray-900">{{ $resa->nbenfant }}</span> Enfant(s)
                                </div>
                                @endif
                                @if($resa->nbbebe > 0)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-baby text-gray-400"></i>
                                    <span class="font-bold text-gray-900">{{ $resa->nbbebe }}</span> Bébé(s)
                                </div>
                                @endif
                                @if($resa->nbanimeaux > 0)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-paw text-gray-400"></i>
                                    <span class="font-bold text-gray-900">{{ $resa->nbanimeaux }}</span> Animaux
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Motif si refusé --}}
                        @if($resa->statut_reservation === 'Refusée' && $resa->motif_refus)
                            <div class="mt-4 bg-red-50 p-3 rounded border border-red-100 text-sm text-red-800">
                                <strong>Motif du refus :</strong> {{ $resa->motif_refus }}
                            </div>
                        @endif

                        {{-- BOUTONS ACTIONS & MESSAGERIE --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap justify-end gap-3">
                            
                            {{-- LOGIQUE DE NOTIFICATION MESSAGERIE "EN ATTENTE DE REPONSE" --}}
                            @php
                                $msgNonRepondus = 0;
                                foreach($resa->messages->sortByDesc('dateenvoi') as $msg) {
                                    if ($msg->idutilisateur != Auth::id()) {
                                        $msgNonRepondus++;
                                    } else {
                                        break;
                                    }
                                }
                            @endphp

                            {{-- BOUTON MESSAGERIE (Toggle) --}}
                            <button onclick="toggleMessages({{ $resa->idreservation }})" class="{{ $msgNonRepondus > 0 ? 'bg-blue-100 text-blue-800 border-blue-200 shadow-sm' : 'bg-gray-100 text-gray-700 border-gray-200' }} border px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 relative hover:shadow-md">
                                <i class="fa-regular fa-comments"></i> 
                                {{ $msgNonRepondus > 0 ? 'Répondre' : 'Discussion' }}
                                
                                @if($msgNonRepondus > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 animate-pulse text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white shadow-sm">
                                        {{ $msgNonRepondus }}
                                    </span>
                                @endif
                            </button>

                            @if($resa->statut_reservation === 'En attente')
                                <button onclick="showRefusForm({{ $resa->idreservation }})" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-bold transition">
                                    Refuser
                                </button>

                                <form action="{{ route('proprietaire.accepter', $resa->idreservation) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white hover:bg-green-700 px-6 py-2 rounded-lg text-sm font-bold transition shadow-md">
                                        Accepter
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-gray-400 italic self-center">Statut : {{ $resa->statut_reservation }}</span>
                            @endif
                        </div>

                        {{-- ZONE MESSAGERIE DÉPLIABLE --}}
                        <div id="messages-section-{{ $resa->idreservation }}" class="hidden mt-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-comments"></i> Historique des échanges
                            </h4>

                            <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                @forelse($resa->messages as $msg)
                                    <div class="flex {{ $msg->idutilisateur == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-[85%] p-3 rounded-2xl text-sm {{ $msg->idutilisateur == Auth::id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-700 rounded-bl-none shadow-sm' }}">
                                            <p class="whitespace-pre-line">{{ $msg->contenu }}</p>
                                            <p class="text-[10px] mt-1 opacity-70 {{ $msg->idutilisateur == Auth::id() ? 'text-blue-100 text-right' : 'text-gray-400' }}">
                                                {{ \Carbon\Carbon::parse($msg->dateenvoi)->format('d/m H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-400 text-xs italic py-2">Aucun message pour le moment.</p>
                                @endforelse
                            </div>

                            {{-- Formulaire de réponse --}}
                            <form action="{{ route('reservations.message', $resa->idreservation) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="message" required class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Écrire une réponse...">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-lg flex items-center justify-center transition">
                                    <i class="fa-regular fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>

                        {{-- ZONE REFUS (Caché par défaut) --}}
                        <div id="form-refus-{{ $resa->idreservation }}" class="hidden mt-4 bg-red-50 p-4 rounded-lg border border-red-100">
                            <form action="{{ route('proprietaire.refuser', $resa->idreservation) }}" method="POST">
                                @csrf
                                <label class="block text-xs font-bold text-red-700 mb-2 uppercase">Pourquoi refusez-vous ? (Optionnel)</label>
                                <textarea name="motif" rows="2" class="w-full text-sm p-3 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 mb-3 bg-white" placeholder="Ex: Logement indisponible, travaux..."></textarea>
                                
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="hideRefusForm({{ $resa->idreservation }})" class="text-gray-500 hover:text-gray-700 text-sm font-semibold px-3 py-2">Annuler</button>
                                    <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition">Confirmer le refus</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function showRefusForm(id) {
        document.getElementById('messages-section-' + id).classList.add('hidden');
        document.getElementById('form-refus-' + id).classList.remove('hidden');
    }

    function hideRefusForm(id) {
        document.getElementById('form-refus-' + id).classList.add('hidden');
    }

    function toggleMessages(id) {
        document.getElementById('form-refus-' + id).classList.add('hidden');
        const section = document.getElementById('messages-section-' + id);
        section.classList.toggle('hidden');
    }
</script>
@endsection