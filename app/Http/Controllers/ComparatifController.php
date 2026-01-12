<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Annonce;
use Carbon\Carbon;

class ComparatifController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'Directeur') {
            return redirect()->route('home');
        }

        $now = Carbon::now();
        $debutMois = $now->startOfMonth()->format('Y-m-d');
        $finMois = $now->endOfMonth()->format('Y-m-d');
        $colonneDate = 'iddate'; 

        // --- STATS GLOBALES ---
        $reservationsMois = Reservation::join('dates', 'reservation.' . $colonneDate, '=', 'dates.iddate')
            ->whereBetween('dates.dateacte', [$debutMois, $finMois])->count();

        $totalAnnonces = Annonce::where('statutannonce', 'En ligne')->count();

        $dernieresReservations = Reservation::with(['annonce.ville'])
            ->join('dates as d_debut', 'reservation.' . $colonneDate, '=', 'd_debut.iddate')
            ->orderBy('d_debut.dateacte', 'desc')
            ->select('reservation.*')->take(5)->get();

        $caEstime = Reservation::join('dates', 'reservation.' . $colonneDate, '=', 'dates.iddate')
            ->join('annonce', 'reservation.idannonce', '=', 'annonce.idannonce')
            ->join('prixperiode', 'annonce.idannonce', '=', 'prixperiode.idannonce')
            ->whereBetween('dates.dateacte', [$debutMois, $finMois])->sum('prixperiode.prix');

        // --- GRAPHIQUE 1 (Lignes) ---
        $rawStats = Reservation::join('dates', 'reservation.'.$colonneDate, '=', 'dates.iddate')
            ->join('annonce', 'reservation.idannonce', '=', 'annonce.idannonce')
            ->join('typehebergement', 'annonce.idtypehebergement', '=', 'typehebergement.idtypehebergement')
            ->join('prixperiode', 'annonce.idannonce', '=', 'prixperiode.idannonce')
            ->select(
                DB::raw("TO_CHAR(dates.dateacte, 'YYYY-MM') as mois"),
                'typehebergement.typehebergement as type_nom',
                DB::raw("SUM(prixperiode.prix) as total_ca")
            )
            ->where('dates.dateacte', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->groupBy('mois', 'type_nom')
            ->orderBy('mois', 'ASC')->get();

        $labels = $rawStats->pluck('mois')->unique()->sort()->values()->all();
        $types = \App\Models\TypeHebergement::pluck('typehebergement')->all();
        $datasets = [];

        // Dataset TOTAL
        $dataTotal = [];
        foreach ($labels as $mois) {
            $dataTotal[] = (float) $rawStats->where('mois', $mois)->sum('total_ca');
        }
        $datasets[] = [
            'label' => 'TOTAL GÉNÉRAL',
            'data' => $dataTotal,
            'borderColor' => '#1f2d3d',
            'backgroundColor' => 'transparent',
            'borderWidth' => 3,
            'borderDash' => [5, 5],
            'tension' => 0.3,
            'pointStyle' => 'rectRot',
            'pointRadius' => 6
        ];

        $colors = ['#ec5a13', '#366dc3', '#22c55e', '#f59e0b', '#8b5cf6']; 
        foreach ($types as $index => $type) {
            $dataForType = [];
            foreach ($labels as $mois) {
                $record = $rawStats->where('mois', $mois)->where('type_nom', $type)->first();
                $dataForType[] = $record ? (float) $record->total_ca : 0;
            }
            $datasets[] = [
                'label' => $type, 
                'data' => $dataForType,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => 'transparent',
                'borderWidth' => 2,
                'tension' => 0.3
            ];
        }

        // --- GRAPHIQUE 2 : TOP 10 + AUTRES (MODIFIÉ) ---
        
        // 1. On récupère TOUS les résultats (on enlève ->take(7))
        $allOwnersRaw = Reservation::join('annonce', 'reservation.idannonce', '=', 'annonce.idannonce')
            ->join('prixperiode', 'annonce.idannonce', '=', 'prixperiode.idannonce')
            ->select(
                'annonce.idutilisateur', 
                DB::raw("SUM(prixperiode.prix) as chiffre_affaires"),
                DB::raw("COUNT(reservation.idreservation) as nb_resas")
            )
            ->groupBy('annonce.idutilisateur')
            ->orderBy('chiffre_affaires', 'desc')
            ->get();

        // Tableau temporaire pour stocker les données avant le tri final
        $finalStats = [];

        // 2. Traitement des 10 premiers
        foreach ($allOwnersRaw->take(10) as $stat) {
            $nomAffiche = "User #" . $stat->idutilisateur;
            try {
                $pro = DB::table('professionnel')->where('idutilisateur', $stat->idutilisateur)->first();
                if ($pro) {
                    $nomAffiche = $pro->nomprofessionnel;
                } else {
                    $lienProprio = DB::table('proprietaire')->where('idutilisateur', $stat->idutilisateur)->first();
                    if ($lienProprio) {
                        $part = DB::table('particulier')->where('idparticulier', $lienProprio->idparticulier)->first();
                        if ($part) { $nomAffiche = $part->prenomparticulier . ' ' . $part->nomparticulier; }
                    }
                }
            } catch (\Exception $e) {}

            $finalStats[] = [
                'label' => $nomAffiche,
                'revenue' => (float) $stat->chiffre_affaires,
                'count' => (int) $stat->nb_resas
            ];
        }

        // 3. Calcul de "Autres"
        $others = $allOwnersRaw->skip(10);
        if ($others->count() > 0) {
            // On ajoute "Autres" à la liste
            $finalStats[] = [
                'label' => 'Autres (' . $others->count() . ' user' . ($others->count() > 1 ? 's' : '') . ')',
                'revenue' => (float) $others->sum('chiffre_affaires'),
                'count' => (int) $others->sum('nb_resas')
            ];
        }

        // 4. TRI FINAL : On retrie TOUT le tableau (y compris "Autres") par revenus décroissants
        usort($finalStats, function ($a, $b) {
            return $b['revenue'] <=> $a['revenue']; // Tri décroissant (desc)
        });

        // 5. Extraction vers les colonnes séparées pour ChartJS
        $ownerLabels = array_column($finalStats, 'label');
        $ownerRevenue = array_column($finalStats, 'revenue');
        $ownerCount = array_column($finalStats, 'count');

        $geoData = Reservation::join('annonce', 'reservation.idannonce', '=', 'annonce.idannonce')
            ->join('prixperiode', 'annonce.idannonce', '=', 'prixperiode.idannonce')
            ->join('adresse', 'annonce.idadresse', '=', 'adresse.idadresse')
            ->join('ville', 'annonce.idville', '=', 'ville.idville')
            ->join('dates', 'reservation.'.$colonneDate, '=', 'dates.iddate')
            ->select(
                'annonce.titreannonce',
                'ville.nomville',
                'adresse.latitude',
                'adresse.longitude',
                DB::raw('SUM(prixperiode.prix) as total_ca'), // Le montant des ventes
                DB::raw('COUNT(reservation.idreservation) as nb_resas')
            )
            ->where('dates.dateacte', '>=', Carbon::now()->subMonths(12)->startOfMonth()) // Sur 12 mois
            ->whereNotNull('adresse.latitude') // Sécurité si pas de GPS
            ->whereNotNull('adresse.longitude')
            ->groupBy('annonce.idannonce', 'adresse.latitude', 'adresse.longitude', 'ville.nomville')
            ->get();

        // On récupère le montant max pour calculer la taille relative des bulles (100% = plus grosse bulle)
        $maxRevenue = $geoData->max('total_ca') ?? 1;

        $topCities = $geoData->groupBy('nomville')->map(function ($group) {
            return [
                'nomville' => $group->first()->nomville,
                'total_ca' => $group->sum('total_ca'), // Somme des CA de toutes les annonces de la ville
                'nb_resas' => $group->sum('nb_resas'),
                'nb_annonces' => $group->count() // Nombre d'annonces actives ayant vendu
            ];
        })->sortByDesc('total_ca')->take(5); // On garde le Top 5

        return view('comparatif.comparatif', compact(
            'reservationsMois', 'totalAnnonces', 'dernieresReservations', 'caEstime',
            'labels', 'datasets', 
            'ownerLabels', 'ownerRevenue', 'ownerCount',
            'geoData', 'maxRevenue', 
            'topCities' // <--- NOUVELLE VARIABLE AJOUTÉE
        ));
    }
}