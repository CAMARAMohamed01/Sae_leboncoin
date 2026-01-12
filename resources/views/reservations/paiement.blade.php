@extends('layouts.app')

@section('title', 'Paiement sécurisé')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-80px)] py-12">
    <div class="container mx-auto px-4">
        
        {{-- Navigation / Retour --}}
        <div class="mb-8 max-w-4xl mx-auto">
            <a href="{{ route('reservations.mes_locations') }}" class="text-sm text-gray-500 hover:text-lbc-blue flex items-center gap-1 transition">
                <i class="fa-solid fa-arrow-left"></i> Annuler et retourner à mes locations
            </a>
        </div>

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-8">
            
            <!-- GAUCHE : ACTIONS PAIEMENT -->
            <div class="flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    
                    <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-4 font-sans">
                        Paiement sécurisé
                    </h1>
                    
                    {{-- Message si c'est un supplément --}}
                    @if(isset($dejaPaye) && $dejaPaye > 0)
                        <div class="bg-blue-50 text-blue-800 p-4 rounded-lg mb-6 text-sm border border-blue-100 text-left flex items-start gap-3">
                            <i class="fa-solid fa-circle-info mt-0.5 text-blue-600"></i>
                            <div>
                                <strong>Régularisation :</strong><br>
                                Vous avez déjà réglé une partie de cette réservation. Vous ne payez aujourd'hui que le supplément dû à la modification.
                            </div>
                        </div>
                    @endif

                    <div class="py-8 border-b border-gray-100 mb-8">
                        <p class="text-gray-500 text-sm uppercase font-bold tracking-wider mb-2">Montant à régler</p>
                        <div class="text-5xl font-extrabold text-[#6772e5]">
                            {{ number_format($totalAPayer, 2, ',', ' ') }} <span class="text-2xl">€</span>
                        </div>
                    </div>

                    <!-- OPTION 1 : BOUTON STRIPE (OFFICIEL) -->
                    <form action="{{ route('reservations.payment.stripe', $reservation->idreservation) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#6772e5] hover:bg-[#5469d4] text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-1 active:scale-[0.99] text-lg flex justify-center items-center gap-3">
                            <span>Payer par Carte</span>
                            <i class="fa-brands fa-stripe text-4xl mt-1"></i>
                        </button>
                    </form>
                    
                    <div class="mt-4 flex justify-center gap-4 text-gray-300 text-2xl">
                        <i class="fa-brands fa-cc-visa"></i>
                        <i class="fa-brands fa-cc-mastercard"></i>
                        <i class="fa-brands fa-cc-amex"></i>
                    </div>

                    <!-- OPTION 2 : SIMULATION (POUR VOS TESTS) -->
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-3 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-flask"></i> Zone de test (Simulation)
                        </p>
                        
                        {{-- Ce formulaire pointe vers la méthode de simulation 'processPayment' --}}
                        <form action="{{ route('reservations.payment.process', $reservation->idreservation) }}" method="POST">
                            @csrf
                            {{-- On envoie le montant pour l'enregistrement --}}
                            <input type="hidden" name="montant_total" value="{{ $totalAPayer }}">
                            
                            <button type="submit" class="w-full bg-gray-100 hover:bg-green-50 text-gray-600 hover:text-green-700 font-bold py-3 rounded-lg border border-gray-300 hover:border-green-200 transition flex justify-center items-center gap-2 text-sm">
                                <i class="fa-regular fa-circle-check"></i> Simuler un paiement réussi
                            </button>
                            <p class="text-[10px] text-gray-400 mt-2">Ce bouton valide la commande sans carte bancaire.</p>
                        </form>
                    </div>

                </div>
            </div>

            <!-- DROITE : RÉCAPITULATIF FINANCIER -->
            <div class="w-full md:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-4 border-b border-gray-100">Récapitulatif</h2>
                    
                    <div class="flex gap-3 mb-6">
                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                            @if($reservation->annonce->photos->isNotEmpty())
                                <img src="{{ asset($reservation->annonce->photos->first()->lienurl) }}" class="w-full h-full object-cover">
                            @else
                                 <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">{{ $reservation->annonce->typeHebergement->typehebergement ?? 'Logement' }}</p>
                            <h3 class="font-bold text-gray-900 text-sm line-clamp-2 mb-1">{{ $reservation->annonce->titreannonce }}</h3>
                            <p class="text-xs text-gray-500"><i class="fa-solid fa-location-dot"></i> {{ $reservation->annonce->ville->nomville }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm text-gray-600 border-t border-gray-100 pt-4">
                        <div class="flex justify-between">
                            <span>Hébergement ({{ $reservation->nbjours }} nuits)</span>
                            <span>{{ number_format($total ?? 0, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="underline decoration-dotted cursor-help" title="Frais de fonctionnement de la plateforme">Frais de service (10%)</span>
                            <span>{{ number_format($frais ?? 0, 2, ',', ' ') }} €</span>
                        </div>
                        
                        @if(isset($dejaPaye) && $dejaPaye > 0)
                            <div class="flex justify-between font-bold text-gray-800 border-t border-gray-100 pt-2 mt-2">
                                <span>Total Séjour</span>
                                <span>{{ number_format(($nouveauTotal ?? 0), 2, ',', ' ') }} €</span>
                            </div>
                            <div class="flex justify-between text-green-600 bg-green-50 px-2 py-1 rounded font-bold mt-1">
                                <span>Déjà réglé</span>
                                <span>- {{ number_format($dejaPaye, 2, ',', ' ') }} €</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center text-xl font-extrabold text-[#6772e5] border-t-2 border-dashed border-gray-200 pt-4 mt-4">
                            <span>À payer</span>
                            <span>{{ number_format($totalAPayer, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection