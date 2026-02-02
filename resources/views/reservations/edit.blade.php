@extends('layouts.app')

@section('title', 'Modifier ma réservation')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-80px)] py-8">
    <div class="container mx-auto px-4">
        
        <div class="mb-6">
            <a href="{{ route('reservations.mes_locations') }}" class="text-sm text-gray-500 hover:text-lbc-blue flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Retour à mes locations
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 max-w-6xl mx-auto">

            {{-- COLONNE GAUCHE : FORMULAIRE --}}
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8 mb-8">
                    <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-2 font-sans">Modifier ma réservation</h1>
                    <p class="text-gray-500 mb-8 text-sm">Référence #{{ $reservation->idreservation }} • {{ $reservation->annonce->titreannonce }}</p>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-100">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reservations.update', $reservation->idreservation) }}" method="POST" id="reservation-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            {{-- Date Arrivée --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Arrivée</label>
                                <div class="relative">
                                    <input type="date" name="date_arrivee" id="date_arrivee" required
                                           value="{{ old('date_arrivee', $reservation->dateDebut->dateacte) }}"
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
                                           value="{{ old('date_depart', $reservation->dateFin->dateacte) }}"
                                           class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange cursor-pointer shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-calendar-xmark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-6">

                        {{-- Section Voyageurs Détaillée --}}
                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 flex justify-between">
                                Voyageurs
                                <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded">Capacité max : {{ $reservation->annonce->capacite }} pers.</span>
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {{-- Adultes --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Adultes</label>
                                    <input type="number" name="nbadulte" id="nbadulte" min="1" required
                                           value="{{ old('nbadulte', $reservation->nbadulte) }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Enfants --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Enfants</label>
                                    <input type="number" name="nbenfant" id="nbenfant" min="0"
                                           value="{{ old('nbenfant', $reservation->nbenfant) }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Bébés --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Bébés</label>
                                    <input type="number" name="nbbebe" id="nbbebe" min="0"
                                           value="{{ old('nbbebe', $reservation->nbbebe) }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>

                                {{-- Animaux --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Animaux</label>
                                    <input type="number" name="nbanimeaux" id="nbanimeaux" min="0"
                                           value="{{ old('nbanimeaux', $reservation->nbanimeaux) }}"
                                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange text-center">
                                </div>
                            </div>
                            <p id="capacity-error" class="text-xs text-red-500 mt-2 hidden">Le nombre total de voyageurs dépasse la capacité du logement.</p>
                        </div>

                        <button type="submit" id="submit-btn" class="w-full bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-4 rounded-xl shadow-md transition transform active:scale-[0.99] text-lg">
                            Enregistrer les modifications
                        </button>

                    </form>
                </div>

                {{-- BLOC 2 : MESSAGERIE --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-[#1f2d3d] mb-4 flex items-center gap-2">
                        <i class="fa-regular fa-paper-plane text-lbc-orange"></i>
                        Contacter le propriétaire
                    </h2>
                    
                    {{-- (Ici tu peux réintégrer le bloc messagerie si tu l'as déjà codé ou utiliser un mailto simple) --}}
                    <div class="text-center py-6">
                        <a href="mailto:{{ $reservation->annonce->proprietaire->emailutilisateur ?? '' }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-50 text-blue-700 font-bold rounded-lg hover:bg-blue-100 transition">
                            <i class="fa-regular fa-envelope"></i> Envoyer un email
                        </a>
                    </div>
                </div>

            </div>

            {{-- RÉCAPITULATIF PRIX (Dynamique) --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    
                    <h3 class="font-bold text-gray-900 mb-4">Nouveau tarif estimé</h3>
                    
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span><span id="display-price">{{ $prixNuit }}</span> € x <span id="display-nights">{{ $reservation->nbjours }}</span> nuits</span>
                            <span><span id="calc-subtotal">0</span> €</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="underline decoration-dotted cursor-help" title="Frais de gestion de la plateforme">Frais de service (10%)</span>
                            <span><span id="calc-fees">0</span> €</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-3 mt-2">
                            <span>Total</span>
                            <span><span id="calc-total">0</span> €</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputStart = document.getElementById('date_arrivee');
        const inputEnd = document.getElementById('date_depart');
        
        const inputAdulte = document.getElementById('nbadulte');
        const inputEnfant = document.getElementById('nbenfant');
        const capacityError = document.getElementById('capacity-error');
        const btnSubmit = document.getElementById('submit-btn');
        const maxCapacity = {{ $reservation->annonce->capacite }};

        const displayNights = document.getElementById('display-nights');
        const displayTotal = document.getElementById('calc-total');
        const displaySubtotal = document.getElementById('calc-subtotal');
        const displayFees = document.getElementById('calc-fees');
        
        const pricePerNight = {{ is_numeric($prixNuit) ? $prixNuit : 0 }}; 
        const feesPercentage = 0.10;

        function checkCapacity() {
            const totalHumans = parseInt(inputAdulte.value || 0) + parseInt(inputEnfant.value || 0);
            
            if (totalHumans > maxCapacity) {
                capacityError.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                capacityError.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                calculateTotal();
            }
        }

        function calculateTotal() {
            const startVal = inputStart.value;
            const endVal = inputEnd.value;
            
            const totalHumans = parseInt(inputAdulte.value || 0) + parseInt(inputEnfant.value || 0);
            if (totalHumans > maxCapacity) {
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
        }

        inputStart.addEventListener('change', function() {
            if(inputEnd) inputEnd.min = this.value;
            calculateTotal();
        });
        if(inputEnd) inputEnd.addEventListener('change', calculateTotal);

        if(inputAdulte) inputAdulte.addEventListener('input', checkCapacity);
        if(inputEnfant) inputEnfant.addEventListener('input', checkCapacity);
        
        checkCapacity();
    });
</script>
@endsection