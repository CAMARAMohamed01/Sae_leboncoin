@extends('layouts.app')

@section('content')

{{-- On simule le fond gris clair typique des pages de listing --}}
<div class="min-h-screen bg-[#f4f6f7] py-8">
    <div class="container mx-auto px-4">
        
        {{-- Fil d'ariane style LBC (plus discret) --}}
        <nav class="text-xs text-gray-500 mb-6 flex items-center gap-2 max-w-2xl mx-auto">
            <a href="{{ route('home') }}" class="hover:underline hover:text-[#ff6e14] transition-colors">Accueil</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
            <a href="{{ route('annonces.show', $annonce->idannonce) }}" class="hover:underline hover:text-[#ff6e14] transition-colors font-medium text-gray-600 truncate max-w-[150px]">
                {{ $annonce->titreannonce }}
            </a>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-gray-900 font-semibold">Disponibilités</span>
        </nav>

        <div class="max-w-xl mx-auto bg-white rounded-xl shadow-[0_1px_3px_0_rgba(0,0,0,0.1)] overflow-hidden">
            
            {{-- En-tête du Calendrier --}}
            <div class="p-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-[#1a1a1a] text-center mb-6 font-sans">
                    Quand souhaitez-vous réserver ?
                </h1>

                <div class="flex items-center justify-between px-2">
                    @php
                        $maintenant = \Carbon\Carbon::now();
                        $estMoisActuel = $dateActuelle->format('Ym') <= $maintenant->format('Ym');
                    @endphp

                    {{-- Bouton Précédent --}}
                    @if ($estMoisActuel)
                        <button class="w-10 h-10 flex items-center justify-center rounded-full text-gray-300 cursor-not-allowed bg-gray-50" disabled>
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>
                    @else
                        <a href="{{ route('annonce.calendar', ['id' => $annonce->idannonce, 'date' => $datePrecedente->format('Y-m-d')]) }}" 
                           class="w-10 h-10 flex items-center justify-center rounded-full text-[#1a1a1a] hover:bg-[#ff6e14] hover:text-white transition-all duration-200 bg-gray-50">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    {{-- Titre du Mois --}}
                    <div class="text-center">
                        <span class="block text-lg font-bold text-[#1a1a1a] capitalize">
                            {{ $dateActuelle->locale('fr_FR')->monthName }}
                            <span class="font-normal text-gray-500 ml-1">{{ $dateActuelle->year }}</span>
                        </span>
                    </div>

                    {{-- Bouton Suivant --}}
                    <a href="{{ route('annonce.calendar', ['id' => $annonce->idannonce, 'date' => $dateSuivante->format('Y-m-d')]) }}" 
                       class="w-10 h-10 flex items-center justify-center rounded-full text-[#1a1a1a] hover:bg-[#ff6e14] hover:text-white transition-all duration-200 bg-gray-50">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Grille du Calendrier --}}
            <div class="p-6">
                
                {{-- Jours de la semaine --}}
                <div class="grid grid-cols-7 gap-2 mb-2">
                    @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
                        <div class="text-center text-[11px] font-medium text-gray-400 uppercase py-2">
                            {{ $jour }}
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-7 gap-2">
                    {{-- Cases vides début de mois --}}
                    @php
                        $casesVides = $dateActuelle->copy()->startOfMonth()->dayOfWeekIso - 1;
                        $joursOccupes = $annonce->jours_occupes ?? [];
                    @endphp

                    @for ($j = 0; $j < $casesVides; $j++)
                        <div class="aspect-square"></div>
                    @endfor

                    {{-- Jours du mois --}}
                    @for ($i = 1; $i <= $dateActuelle->daysInMonth; $i++)
                        @php
                            $jourEnCours = $dateActuelle->copy()->day($i);
                            $dateString = $jourEnCours->format('Y-m-d');
                            
                            $estReserve = in_array($dateString, $joursOccupes);
                            $estPasse = $jourEnCours->isPast() && !$jourEnCours->isToday();
                            $estAujourdhui = $jourEnCours->isToday();
                        @endphp

                        <div class="aspect-square flex items-center justify-center rounded-lg text-sm font-medium transition-all duration-200 relative
                            @if ($estReserve)
                                bg-gray-100 text-gray-300 border border-transparent cursor-not-allowed
                            @elseif ($estPasse)
                                bg-white text-gray-300 border border-transparent cursor-not-allowed
                            @else
                                {{-- Style "Disponible" à la LBC : blanc avec bordure, devient orange au survol --}}
                                bg-white text-[#1a1a1a] border border-gray-200 hover:border-[#ff6e14] hover:text-[#ff6e14] hover:bg-orange-50 cursor-pointer shadow-sm
                            @endif
                            {{ $estAujourdhui ? 'ring-2 ring-[#ff6e14] ring-offset-2 font-bold' : '' }}
                        ">
                            
                            {{-- Indicateur "Aujourd'hui" (point discret) --}}
                            @if($estAujourdhui)
                                <span class="absolute top-1 right-1 h-1.5 w-1.5 rounded-full bg-[#ff6e14]"></span>
                            @endif

                            {{-- Barré si réservé --}}
                            @if($estReserve)
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-3/4 h-px bg-gray-300 rotate-45 transform"></div>
                                </div>
                            @endif

                            {{ $i }}
                        </div>
                    @endfor
                </div>

                {{-- Légende (remaniée plus pro) --}}
                <div class="mt-8 flex justify-center gap-6 text-xs text-gray-600 border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full border border-gray-300 bg-white"></span> Disponible
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded bg-gray-100 relative overflow-hidden">
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3/4 h-px bg-gray-400 rotate-45"></div>
                        </span> Réservé
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection