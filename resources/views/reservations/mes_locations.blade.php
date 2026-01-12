@extends('layouts.app')

@section('title', 'Mes locations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-8">
        <h1 class="text-3xl font-extrabold text-[#1f2d3d] font-sans">Mes locations</h1>
        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full">{{ $reservations->count() }} séjours</span>
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

    @if($reservations->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mb-4 text-gray-300">
                <i class="fa-solid fa-suitcase-rolling text-6xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Aucune location prévue</h3>
            <p class="text-gray-500 mb-6">Vous n'avez pas encore réservé de voyage. C'est le moment de partir !</p>
            <a href="{{ route('recherche.index') }}" class="bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                Trouver un logement
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($reservations as $resa)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition group">
                    
                    <!-- Image de l'annonce -->
                    <div class="w-full md:w-72 h-48 md:h-auto bg-gray-200 relative flex-shrink-0">
                        @if($resa->annonce && $resa->annonce->photos->isNotEmpty())
                            <img src="{{ asset($resa->annonce->photos->first()->lienurl) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                <i class="fa-regular fa-image text-3xl"></i>
                            </div>
                        @endif
                        
                        <!-- Dates (Badge sur l'image) -->
                        <div class="absolute bottom-2 left-2 bg-black/70 backdrop-blur-sm text-white text-xs px-2 py-1 rounded flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i>
                            <span>
                                {{ $resa->dateDebut->dateacte ?? '?' }} 
                                <i class="fa-solid fa-arrow-right mx-1 text-gray-400"></i> 
                                {{ $resa->dateFin->dateacte ?? '?' }}
                            </span>
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900 line-clamp-1">
                                    {{ $resa->annonce->titreannonce ?? 'Logement supprimé' }}
                                </h3>
                                
                                {{-- AFFICHAGE DU STATUT DYNAMIQUE --}}
                                @if($resa->statut_reservation === 'En attente')
                                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-orange-200 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> En attente
                                    </span>
                                @elseif($resa->statut_reservation === 'Acceptée')
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-green-200 flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> Confirmé
                                    </span>
                                @elseif($resa->statut_reservation === 'Refusée')
                                    <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-red-200 flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> Refusé
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-500 text-sm mb-2 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot"></i> 
                                {{ $resa->annonce->ville->nomville ?? 'Ville inconnue' }}
                            </p>

                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                                <span><i class="fa-solid fa-users text-gray-400"></i> {{ ($resa->nbadulte ?? 0) + ($resa->nbenfant ?? 0) }} pers.</span>
                                <span><i class="fa-solid fa-moon text-gray-400"></i> {{ $resa->nbjours ?? '?' }} nuits</span>
                            </div>
                            
                            {{-- AFFICHAGE DU MOTIF DE REFUS (Si existant) --}}
                            @if($resa->statut_reservation === 'Refusée' && $resa->motif_refus)
                                <div class="mt-2 bg-red-50 border border-red-100 rounded-lg p-3 text-sm text-red-800">
                                    <strong>Motif du refus :</strong> "{{ $resa->motif_refus }}"
                                </div>
                            @endif

                            {{-- ZONE FORMULAIRE CONTACT (Cachée par défaut) --}}
                            <div id="contact-form-{{ $resa->idreservation }}" class="hidden mt-4 bg-gray-50 p-4 rounded-xl border border-gray-200 transition-all duration-300">
                                
                                {{-- HISTORIQUE DES MESSAGES --}}
                                @if(isset($resa->messages) && $resa->messages->count() > 0)
                                    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-comments"></i> Historique des échanges
                                    </h4>
                                    
                                    {{-- Ajout de la classe 'chat-container' pour le scroll auto JS --}}
                                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar chat-container">
                                        @foreach($resa->messages as $msg)
                                            <div class="flex {{ $msg->idutilisateur == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                                <div class="max-w-[85%] p-3 rounded-2xl text-sm {{ $msg->idutilisateur == Auth::id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-700 rounded-bl-none shadow-sm' }}">
                                                    <p class="whitespace-pre-line">{{ $msg->contenu }}</p>
                                                    <p class="text-[10px] mt-1 opacity-70 {{ $msg->idutilisateur == Auth::id() ? 'text-blue-100 text-right' : 'text-gray-400' }}">
                                                        {{ \Carbon\Carbon::parse($msg->dateenvoi)->format('d/m/Y à H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="border-gray-200 mb-4">
                                @endif

                                {{-- FORMULAIRE --}}
                                <form action="{{ route('reservations.message', $resa->idreservation) }}" method="POST">
                                    @csrf
                                    <label class="block text-xs font-bold text-blue-800 uppercase mb-2">Votre message</label>
                                    <textarea name="message" rows="2" required class="w-full text-sm p-3 border border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-3" placeholder="Écrire un message au propriétaire..."></textarea>
                                    
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="toggleContact({{ $resa->idreservation }})" class="text-gray-500 text-sm font-bold px-3 py-2 hover:bg-gray-100 rounded transition">Fermer</button>
                                        <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition flex items-center gap-2">
                                            <i class="fa-regular fa-paper-plane"></i> Envoyer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap justify-end items-center gap-3 pt-4 border-t border-gray-100">
                            
                            {{-- LOGIQUE DE NOTIFICATION "NON LU" --}}
                            @php
                                $msgNonRepondus = 0;
                                if(isset($resa->messages) && $resa->messages->count() > 0) {
                                    // On regarde les messages du plus récent au plus ancien
                                    foreach($resa->messages->sortByDesc('dateenvoi') as $msg) {
                                        // Si le message ne vient PAS de moi (le locataire), c'est une réponse du proprio non lue
                                        if ($msg->idutilisateur != Auth::id()) {
                                            $msgNonRepondus++;
                                        } else {
                                            break; // J'ai parlé en dernier, donc c'est "lu"
                                        }
                                    }
                                }
                            @endphp

                            {{-- Bouton CONTACTER (Badge notif intelligent) --}}
                            <button onclick="toggleContact({{ $resa->idreservation }})" class="relative px-4 py-2 text-sm font-bold {{ $msgNonRepondus > 0 ? 'text-blue-700 bg-blue-50 border-blue-200' : 'text-gray-600 bg-white border-gray-300' }} border rounded-lg hover:shadow-sm transition flex items-center gap-2">
                                <i class="fa-regular fa-envelope"></i> 
                                
                                {{-- Texte du bouton --}}
                                @if($msgNonRepondus > 0)
                                    Répondre
                                @elseif(isset($resa->messages) && $resa->messages->count() > 0)
                                    Discussion
                                @else
                                    Contacter
                                @endif

                                {{-- Badge Notification --}}
                                @if($msgNonRepondus > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 animate-pulse text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white shadow-sm">
                                        {{ $msgNonRepondus }}
                                    </span>
                                @endif
                            </button>

                            {{-- Bouton MODIFIER --}}
                            @if($resa->statut_reservation !== 'Refusée')
                                <a href="{{ route('reservations.edit', $resa->idreservation) }}" class="px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 border border-gray-200">
                                    <i class="fa-solid fa-pen"></i> Modifier
                                </a>
                            @endif

                            {{-- Bouton INCIDENT --}}
                            @if($resa->statut_reservation === 'Acceptée' && Route::has('incident.create'))
                                <a href="{{ route('incident.create', $resa->idreservation) }}" class="px-4 py-2 text-sm font-bold text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition flex items-center gap-2">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Signaler pb
                                </a>
                            @endif

                            {{-- Bouton VOIR LOGEMENT --}}
                            @if($resa->annonce)
                                <a href="{{ route('annonces.show', $resa->annonce->idannonce) }}" class="px-4 py-2 text-sm font-bold text-white bg-lbc-blue rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-sm">
                                    Voir logement <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function toggleContact(id) {
        const form = document.getElementById('contact-form-' + id);
        form.classList.toggle('hidden');
        
        // Scroll automatique vers le bas de la discussion à l'ouverture
        if (!form.classList.contains('hidden')) {
            const chatContainer = form.querySelector('.chat-container');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }
    }
</script>
@endsection