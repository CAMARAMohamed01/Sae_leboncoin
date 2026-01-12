@extends('layouts.app')

@section('title', 'Réserver un séjour')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-80px)] py-8">
    <div class="container mx-auto px-4">
        
        <div class="mb-6">
            <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="text-sm text-gray-500 hover:text-lbc-blue flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Retour à l'annonce
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 max-w-6xl mx-auto">

            {{-- COLONNE GAUCHE : FORMULAIRE --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
                    <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-2 font-sans">Demande de réservation</h1>
                    <p class="text-gray-500 mb-8 text-sm">
                        @auth
                            Vous ne serez débité que si le propriétaire accepte votre demande.
                        @else
                            Veuillez sélectionner vos dates pour estimer le prix.
                        @endauth
                    </p>

                    <form action="{{ route('reservations.store', $annonce->idannonce) }}" method="POST" id="reservation-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            {{-- Date Arrivée --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Arrivée</label>
                                <div class="relative">
                                    <input type="date" name="date_arrivee" id="date_arrivee" required
                                           class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange cursor-pointer shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Date Départ --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Départ</label>
                                <div class="relative">
                                    <input type="date" name="date_depart" id="date_depart" required
                                           class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange cursor-pointer shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-calendar-xmark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-6">

                        {{-- Section Voyageurs (DÉTAILLÉE) --}}
                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 flex justify-between">
                                Voyageurs
                                <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded">Capacité max : {{ $annonce->capacite }} pers.</span>
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {{-- Adultes --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Adultes</label>
                                    <input type="number" name="nbadulte" id="nbadulte" min="1" value="1" required
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Enfants --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Enfants</label>
                                    <input type="number" name="nbenfant" id="nbenfant" min="0" value="0"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Bébés --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Bébés</label>
                                    <input type="number" name="nbbebe" id="nbbebe" min="0" value="0"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Animaux --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Animaux</label>
                                    <input type="number" name="nbanimeaux" id="nbanimeaux" min="0" value="0"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>
                            </div>
                            <p id="capacity-error" class="text-xs text-red-500 mt-2 hidden">Le nombre total de voyageurs dépasse la capacité du logement.</p>
                        </div>

                        <hr class="border-gray-100 my-6">

                        {{-- Message Propriétaire --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Message au propriétaire (Optionnel)</label>
                            <textarea rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" placeholder="Bonjour, je suis intéressé par votre logement..."></textarea>
                        </div>

                        {{-- BOUTON D'ACTION --}}
                        @auth
                            <button type="submit" id="submit-btn" class="w-full bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-4 rounded-xl shadow-md transition transform active:scale-[0.99] text-lg flex justify-center items-center gap-2">
                                <span>Envoyer la demande</span>
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-3">Aucun montant ne sera débité pour l'instant.</p>
                         @else
                            {{-- MODIFICATION ICI : Ajout du paramètre 'redirect' --}}
                            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="w-full bg-lbc-blue hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-md transition transform active:scale-[0.99] text-lg flex justify-center items-center gap-2 text-center no-underline">
                                <span>Se connecter pour réserver</span>
                                <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                            </a>
                            <p class="text-center text-xs text-gray-400 mt-3">Vous devez avoir un compte pour finaliser la réservation.</p>
                        @endauth

                    </form>
                </div>
            </div>

            {{-- COLONNE DROITE : RÉCAPITULATIF --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    
                    <h3 class="font-bold text-gray-900 mb-4">Détails du prix</h3>
                    
                    {{-- Info Annonce --}}
                    <div class="flex gap-3 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($annonce->photos->isNotEmpty())
                                <img src="{{ asset($annonce->photos->first()->lienurl) }}" class="w-full h-full object-cover">
                            @else
                                 <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">{{ $annonce->typeHebergement->typehebergement ?? 'Logement' }}</p>
                            <h3 class="font-bold text-gray-900 text-sm line-clamp-2 mb-1">{{ $annonce->titreannonce }}</h3>
                            <p class="text-xs text-gray-500"><i class="fa-solid fa-location-dot"></i> {{ $annonce->ville->nomville }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span><span id="display-price">{{ $prixNuit }}</span> € x <span id="display-nights">0</span> nuits</span>
                            <span><span id="calc-subtotal">0</span> €</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="underline decoration-dotted cursor-help" title="Frais de gestion de la plateforme">Frais de service</span>
                            <span><span id="calc-fees">0</span> €</span>
                        </div>
                    </div>

                    <hr class="border-gray-200 my-4">

                    <div class="flex justify-between items-center text-lg font-extrabold text-gray-900">
                        <span>Total</span>
                        <span><span id="calc-total">0</span> €</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT CALCULATEUR --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const inputStart = document.getElementById('date_arrivee');
        const inputEnd = document.getElementById('date_depart');
        const btnSubmit = document.getElementById('submit-btn');
        
        // Inputs Voyageurs
        const inputAdulte = document.getElementById('nbadulte');
        const inputEnfant = document.getElementById('nbenfant');
        const capacityError = document.getElementById('capacity-error');
        const maxCapacity = {{ $annonce->capacite }};

        const displayNights = document.getElementById('display-nights');
        const displaySubtotal = document.getElementById('calc-subtotal');
        const displayFees = document.getElementById('calc-fees');
        const displayTotal = document.getElementById('calc-total');

        const pricePerNight = {{ is_numeric($prixNuit) ? $prixNuit : 0 }}; 
        const feesPercentage = 0.10; 

        // Fonction pour vérifier la capacité
        function checkCapacity() {
            const totalHumans = parseInt(inputAdulte.value || 0) + parseInt(inputEnfant.value || 0);
            
            if (totalHumans > maxCapacity) {
                capacityError.classList.remove('hidden');
                if(btnSubmit) btnSubmit.disabled = true;
                if(btnSubmit) btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                return false;
            } else {
                capacityError.classList.add('hidden');
                // On réactive le bouton seulement si les dates sont OK (géré par calculateTotal)
                calculateTotal(); 
                return true;
            }
        }

        // Listeners sur les champs voyageurs
        if(inputAdulte) inputAdulte.addEventListener('input', checkCapacity);
        if(inputEnfant) inputEnfant.addEventListener('input', checkCapacity);

        // ... (Suite logique dates inchangée) ...
        const formatDate = (date) => date.toISOString().split('T')[0];
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        inputStart.min = formatDate(today);
        inputEnd.min = formatDate(tomorrow);

        function calculateTotal() {
            const startVal = inputStart.value;
            const endVal = inputEnd.value;

            // On vérifie aussi la capacité avant de valider
            const totalHumans = parseInt(inputAdulte.value || 0) + parseInt(inputEnfant.value || 0);
            if (totalHumans > maxCapacity) {
                // Si capacité dépassée, on ne calcule pas et on laisse le bouton désactivé
                if(btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                }
                return;
            }

            if (startVal && endVal) {
                const startDate = new Date(startVal);
                const endDate = new Date(endVal);
                const diffTime = endDate - startDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > 0) {
                    const subtotal = pricePerNight * diffDays;
                    const fees = Math.round(subtotal * feesPercentage);
                    const total = subtotal + fees;

                    displayNights.innerText = diffDays;
                    displaySubtotal.innerText = subtotal;
                    displayFees.innerText = fees;
                    displayTotal.innerText = total;

                    if(btnSubmit) {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                } else {
                    resetDisplay();
                }
            } else {
                resetDisplay();
            }
        }

        function resetDisplay() {
            displayNights.innerText = "0";
            displaySubtotal.innerText = "0";
            displayFees.innerText = "0";
            displayTotal.innerText = "0";
            if(btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        inputStart.addEventListener('change', function() {
            if(inputEnd) inputEnd.min = this.value;
            calculateTotal();
        });

        inputEnd.addEventListener('change', calculateTotal);
    });
</script>
@endsection