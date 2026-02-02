@extends('layouts.app')

@section('title', 'Tableau de bord Directeur')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2d3d]">Tableau de bord Directeur</h1>
            <p class="text-gray-500 mt-1">Résumé de l'activité pour {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="bg-lbc-orange/10 text-lbc-orange px-4 py-2 rounded-full font-bold text-sm border border-lbc-orange/20">
            <i class="fa-solid fa-user-shield mr-2"></i> Accès Administrateur
        </div>
    </div>

    {{-- Cartes de Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Réservations (Mois)</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $reservationsMois }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-lbc-blue rounded-full flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Annonces en ligne</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalAnnonces }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-lbc-orange rounded-full flex items-center justify-center text-xl">
                <i class="fa-solid fa-house"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Volume estimé (Mois)</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($caEstime, 0, ',', ' ') }} €</p>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-xl">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-10">
        <h3 class="font-bold text-gray-800 mb-4">Évolution du Chiffre d'Affaires (12 derniers mois)</h3>
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-4">Top 10 Propriétaires (Chiffre d'affaires)</h3>
        <div class="relative h-72 w-full">
            <canvas id="ownersChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-10">
        <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot text-lbc-blue"></i>
            Performance Géographique (12 derniers mois)
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-auto lg:h-96">
            
            {{-- COLONNE GAUCHE : CLASSEMENT DES VILLES --}}
            <div class="overflow-y-auto pr-2">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Top villes par Revenus</h4>
                
                @if($topCities->isEmpty())
                    <p class="text-gray-500 text-sm italic">Aucune donnée géographique disponible.</p>
                @else
                    <div class="space-y-3">
                        @foreach($topCities as $city)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-orange-50 transition border border-gray-100">
                                <div class="flex items-center gap-3">
                                    {{-- Numéro du classement --}}
                                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center font-bold text-sm text-lbc-blue shadow-sm">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $city['nomville'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $city['nb_annonces'] }} annonce(s) concernée(s)
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lbc-orange">
                                        {{ number_format($city['total_ca'], 0, ',', ' ') }} €
                                    </p>
                                    <p class="text-xs text-gray-500 font-semibold">
                                        {{ $city['nb_resas'] }} résa.
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- COLONNE DROITE : LA CARTE --}}
            <div class="relative w-full h-96 lg:h-full rounded-xl overflow-hidden border border-gray-300 shadow-inner">
                <div id="mapSales" class="w-full h-full z-0"></div>
                
                {{-- Petite légende flottante sur la carte --}}
                <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-3 py-2 rounded shadow text-xs z-[1000] pointer-events-none">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-3 h-3 rounded-full bg-lbc-orange opacity-60"></span>
                        <span class="text-gray-600">Volume de ventes</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Tableau des dernières réservations --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Dernières réservations enregistrées</h3>
            <button class="text-xs font-bold text-lbc-blue hover:underline">Voir tout l'historique</button>
        </div>

        
        @if($dernieresReservations->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <i class="fa-regular fa-folder-open text-3xl mb-3 block"></i>
                Aucune réservation trouvée pour le moment.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                        <tr>
                            <th class="px-6 py-3">Logement</th>
                            <th class="px-6 py-3">Locataire</th>
                            <th class="px-6 py-3">Ville</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($dernieresReservations as $resa)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $resa->annonce->titreannonce ?? 'Annonce supprimée' }}
                            </td>
                            <td class="px-6 py-4">
                                {{-- Si tu as une relation utilisateur sur la réservation --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span>User #{{ $resa->idutilisateur }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 py-1 px-2 rounded text-xs font-bold">
                                    {{ $resa->annonce->ville->nomville ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('annonces.show', $resa->idannonce) }}" class="text-lbc-blue hover:underline font-bold text-xs">
                                    Voir l'annonce
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        var mapSales = L.map('mapSales').setView([46.603354, 1.888334], 6);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(mapSales);

        var locations = {!! json_encode($geoData) !!};
        var maxRev = {{ $maxRevenue }};

        locations.forEach(function(loc) {
            
            var radiusSize = 5 + ((loc.total_ca / maxRev) * 25);

            var circle = L.circleMarker([loc.latitude, loc.longitude], {
                color: '#ec5a13',       
                fillColor: '#ec5a13',   
                fillOpacity: 0.6,       
                weight: 1,              
                radius: radiusSize      
            }).addTo(mapSales);

            var popupContent = `
                <div class="text-center">
                    <b class="text-gray-900">${loc.nomville}</b><br>
                    <span class="text-xs text-gray-500">${loc.titreannonce}</span>
                    <div class="mt-2 text-lbc-orange font-bold text-lg">
                        ${new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(loc.total_ca)}
                    </div>
                    <div class="text-xs font-semibold text-gray-600">
                        ${loc.nb_resas} réservation(s)
                    </div>
                </div>
            `;

            circle.bindPopup(popupContent);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const debugData = {
            labels: {!! json_encode($ownerLabels ?? []) !!},
            revenu: {!! json_encode($ownerRevenue ?? []) !!},
            count: {!! json_encode($ownerCount ?? []) !!}
        };
        console.log("Données reçues pour le graphique :", debugData);

        const canvas1 = document.getElementById('revenueChart');
        if (canvas1) {
            new Chart(canvas1.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels ?? []) !!},
                    datasets: {!! json_encode($datasets ?? []) !!}
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        const canvas2 = document.getElementById('ownersChart');
        if (canvas2) {
            new Chart(canvas2.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: debugData.labels, 
                    datasets: [
                        {
                            label: 'Chiffre d\'Affaires (€)',
                            data: debugData.revenu,
                            backgroundColor: '#ec5a13', 
                            order: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Nombre Réservations',
                            data: debugData.count,
                            borderColor: '#1f2d3d', 
                            backgroundColor: 'white',
                            type: 'line', 
                            borderWidth: 2,
                            pointRadius: 4,
                            order: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: { display: true, text: 'Revenus (€)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Réservations' }
                        }
                    }
                }
            });
        } else {
            console.error("Impossible de trouver le canvas 'ownersChart' dans le HTML !");
        }
    });
</script>