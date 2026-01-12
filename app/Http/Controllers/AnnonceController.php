<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Adresse;
use App\Models\Ville;
use App\Models\TypeHebergement;
use App\Models\TypeEquipement;
use App\Models\Dates;
use App\Models\PrixPeriode;
use App\Models\TypeService;
use App\Models\ConditionHebergement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage; 
use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Reservation;
use App\Models\Reglement;
use App\Models\Incident;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::with(['ville', 'photos'])->get();

        return view("home", [
            'annonces' => $annonces,
            'searchCity' => ''
        ]);
    }

    public function rechercheParVille(Request $request)
    {

        $villeRecherchee = $request->input('localisation');
        $typeRecherche = $request->input('type_hebergement');
        
        $prixMin = $request->input('prix_min');
        $prixMax = $request->input('prix_max');
        $chambres = $request->input('chambres');

        $filtreAnimaux = $request->has('animaux'); 
        $filtreFumeur = $request->has('fumeur');

        $query = Annonce::query();

        $query->join('ville', 'annonce.idville', '=', 'ville.idville')
              ->join('adresse', 'annonce.idadresse', '=', 'adresse.idadresse')
              ->leftJoin('photo', 'annonce.idannonce', '=', 'photo.idannonce')
              ->leftJoin('prixperiode', 'annonce.idannonce', '=', 'prixperiode.idannonce')
              ->leftJoin('conditionhebergement', 'annonce.idconditionheb', '=', 'conditionhebergement.idconditionheb');

        if (!empty($villeRecherchee)) {
            $query->where('ville.nomville', 'ILIKE', '%' . $villeRecherchee . '%');
        }

        if (!empty($typeRecherche)) {
            $query->where('annonce.idtypehebergement', $typeRecherche);
        }

        if ($request->has('animaux')) {
            $query->where('conditionhebergement.animauxacceptes', $request->boolean('animaux'));
        }

        if ($request->has('fumeur')) {
            $query->where('conditionhebergement.fumeur', $request->boolean('fumeur'));
        }

        if ($prixMin) {
            $query->where('prixperiode.prix', '>=', $prixMin);
        }
        if ($prixMax) {
            $query->where('prixperiode.prix', '<=', $prixMax);
        }
        if ($chambres) {
            $query->where('annonce.nbchambre', '>=', $chambres);
        }

        $query->select('annonce.*', 'ville.nomville', 'adresse.latitude', 'adresse.longitude')
              ->distinct();

        if ($request->has('animaux') || $request->has('fumeur')) {
            dd($query->toSql(), $query->getBindings());
        }

        $annonces = $query->with(['ville', 'photos', 'typeHebergement', 'prixPeriodes', 'conditionHebergement'])->get();

        foreach($annonces as $annonce) {
            $annonce->prix_periodes_min_prix = $annonce->prixPeriodes->min('prix');
        }

        return view('home', [
            'annonces' => $annonces, 
            'searchCity' => $villeRecherchee,
            'typesHebergement' => TypeHebergement::all()
        ]);
    }

    public function show($id)
    {
        $annonce = Annonce::with([
            'ville', 'typeHebergement', 'photos', 'conditionHebergement', 'equipements', 'services', 'dateEnregistrement',
            'prixPeriodes', 
            'proprietaire.particulier', 
            'proprietaire.professionnel'
        ])->findOrFail($id);
        
        $prixMin = $annonce->prixPeriodes?->min('prix');

        $proprietaire = $annonce->proprietaire;
        $detailProfessionnel = null; 

        if ($proprietaire && $proprietaire->professionnel) {
            $detailProfessionnel = $proprietaire->professionnel; 
        }

        $proprietaireperso = $proprietaire->particulier ?? $proprietaire; 

        $annoncesSimilaires = Annonce::query()
            ->with(['ville', 'typeHebergement', 'photos'])
            ->withMin('prixPeriodes', 'prix') 
            ->where('idtypehebergement', $annonce->idtypehebergement)
            ->where('idannonce', '!=', $annonce->idannonce)
            ->take(3)
            ->get();

        return view('annonces.show', compact('annonce', 'prixMin', 'annoncesSimilaires', 'detailProfessionnel', 'proprietaireperso'));
    }

    public function showallannonces($id)
    {
        $annonce = Annonce::with([
            'ville', 'typeHebergement', 'photos', 'conditionHebergement', 'equipements', 'services', 'dateEnregistrement',
            'prixPeriodes', 
            'proprietaire.particulier', 
            'proprietaire.professionnel'
        ])->findOrFail($id);
        
        $prixMin = $annonce->prixPeriodes->min('prix');

        $proprietaire = $annonce->proprietaire;
        $detailProfessionnel = null; 

        if ($proprietaire && $proprietaire->professionnel) {
            $detailProfessionnel = $proprietaire->professionnel; 
        }

        $proprietaireperso = $proprietaire->particulier ?? $proprietaire; 

        $annoncesSimilaires = Annonce::query()
            ->with(['ville', 'typeHebergement', 'photos'])
            ->withMin('prixPeriodes', 'prix') 
            ->where('idtypehebergement', $annonce->idtypehebergement)
            ->where('idannonce', '!=', $annonce->idannonce)
            ->take(3)
            ->get();

        return view('annonces.show', compact('annonce', 'prixMin', 'annoncesSimilaires', 'detailProfessionnel', 'proprietaireperso'));
    }

    public function mesAnnonces()
    {
        $userId = Auth::id();
        
        $annonces = Annonce::where('idutilisateur', $userId)
            ->with(['ville', 'photos', 'typeHebergement', 'dateEnregistrement'])
            ->withMin('prixPeriodes', 'prix')
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('annonces.mes_annonces', compact('annonces'));
    }

    public function mesFavoris()
    {
        $user = Auth::user();
        $annonces = $user->favoris()
            ->with(['ville', 'photos', 'typeHebergement', 'prixPeriodes'])
            ->get();

        return view('annonces.mes_favoris', compact('annonces'));
    }

    public function create()
    {
        $typesHebergement = TypeHebergement::all();
        return view('annonces.create', compact('typesHebergement'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'type_hebergement' => 'required|exists:typehebergement,idtypehebergement',
            'prix' => 'required|numeric|min:1',
            'adresse_numero' => 'nullable|integer',
            'adresse_rue' => 'required|string|max:50',
            'ville_nom' => 'required|string|max:50',
            'ville_cp' => 'required|string|max:5',
            'capacite' => 'required|integer|min:1',
            'chambres' => 'required|integer|min:0',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            
        ], [
            'photos.required' => 'Vous devez ajouter au moins une photo.',
            'photos.*.image' => 'Les fichiers doivent être des images.',
            'photos.*.max' => 'Chaque image ne doit pas dépasser 2 Mo.'
        ]);

        try {
            DB::beginTransaction();

            $ville = Ville::where('nomville', $request->ville_nom)
                          ->where('cpville', $request->ville_cp)
                          ->first();

            if (!$ville) {
                $ville = new Ville();
                $ville->nomville = $request->ville_nom;
                $ville->cpville = $request->ville_cp;
                $ville->t_108 = 1; 
                $ville->save();
            }

            $adresse = new Adresse();
            $adresse->idville = $ville->idville;
            $adresse->nomrue = $request->adresse_rue;
            $adresse->voie = $request->adresse_numero;

            try {
                $queryAdresse = sprintf('%s %s %s %s', 
                    $request->adresse_numero ?? '', 
                    $request->adresse_rue, 
                    $request->ville_cp, 
                    $request->ville_nom
                );

                $response = Http::timeout(5)->get('https://api-adresse.data.gouv.fr/search/', [
                    'q' => $queryAdresse,
                    'limit' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['features'])) {
                        $coords = $data['features'][0]['geometry']['coordinates'];
                        $adresse->longitude = $coords[0];
                        $adresse->latitude = $coords[1];
                    }
                }
            } catch (\Exception $e) {
            }

            $adresse->save();

            $today = Carbon::today()->format('Y-m-d');
            $dateEnr = Dates::firstOrCreate(['dateacte' => $today]);

            $annonce = new Annonce();
            $annonce->titreannonce = $request->titre;
            $annonce->descriptionannonce = $request->description;
            $annonce->idtypehebergement = $request->type_hebergement;
            $annonce->idville = $ville->idville;
            $annonce->idadresse = $adresse->idadresse;
            $annonce->iddate = $dateEnr->iddate;
             
            $annonce->idconditionheb = 1;
            
            $annonce->idutilisateur = Auth::id();
            $annonce->nbchambre = $request->chambres;
            $annonce->capacite = $request->capacite;
            $annonce->statutannonce = 'En ligne';
            $annonce->save();

            $prix = new PrixPeriode();
            $prix->idannonce = $annonce->idannonce;
            $prix->nomperiode = 'Basse saison';
            $prix->prix = $request->prix;
            $prix->save();

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $key => $file) {
                    $path = $file->store('annonces', 'public');
                    Photo::create([
                        'lienurl' => '/storage/' . $path,
                        'legende' => $request->titre . ' - Photo ' . ($key + 1),
                        'idannonce' => $annonce->idannonce
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('annonces.show', $annonce->idannonce)
                            ->with('success', 'Votre annonce a été publiée avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur lors de la publication : " . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);

        if ($annonce->idutilisateur !== Auth::id()) {
            return back()->withErrors(['error' => "Action non autorisée. Vous ne pouvez supprimer que vos propres annonces."]);
        }

        try {
            DB::beginTransaction();

            foreach ($annonce->photos as $photo) {
                $relativePath = str_replace('/storage/', 'public/', $photo->lienurl);
                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }
            $annonce->photos()->delete();

            
            $annonce->prixPeriodes()->delete(); 
            $annonce->equipements()->detach(); 
            $annonce->services()->detach();
            
            // Supprimer les Favoris (Table pivot)
            DB::table('mettre_au_favoris')->where('idannonce', $annonce->idannonce)->delete();

            
            $annonce->avis()->delete();

            
            $reservations = Reservation::where('idannonce', $annonce->idannonce)->get();
            
            foreach ($reservations as $reservation) {
                // Pour chaque réservation, on supprime ses dépendances d'abord (car RESTRICT)
                
                // Supprimer les règlements associés
                Reglement::where('idreservation', $reservation->idreservation)->delete();
                
                // Supprimer les incidents associés
                Incident::where('idreservation', $reservation->idreservation)->delete();
                
                // Supprimer les voyageurs associés (Table voyageur)
                DB::table('voyageur')->where('idreservation', $reservation->idreservation)->delete();
                
                // Enfin, supprimer la réservation elle-même
                $reservation->delete();
            }
            
            // Supprimer l'annonce
            $annonce->delete();

            DB::commit();

            return redirect()->route('annonces.mes_annonces')
                             ->with('success', 'L\'annonce et tout son historique (réservations, avis) ont été supprimés avec succès.');

        } catch (QueryException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Erreur SQL lors de la suppression : " . $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Une erreur inattendue est survenue : " . $e->getMessage()]);
        }
    }
    public function pageReservation($id)
{
    $annonce = \App\Models\Annonce::findOrFail($id);

    return view('reserver.reservervisiteur', [
        'annonce' => $annonce
    ]);
}
public function pagecalendrierreservation($id)
{
    $annonce = \App\Models\Annonce::findOrFail($id);

    return view('calendrierreservation.showdispocalendrier', [
        'annonce' => $annonce
    ]);
}
public function showcalendar(Request $request, $id)
    {
        // 1. Récupérer l'annonce
        $annonce = Annonce::findOrFail($id);

        // 2. Gestion de la date
        $dateActuelle = $request->has('date') 
                        ? Carbon::parse($request->date) 
                        : now();
        
        // On se cale toujours sur le 1er du mois pour l'affichage correct
        $dateActuelle->startOfMonth();

        $datePrecedente = $dateActuelle->copy()->subMonth();
        $dateSuivante = $dateActuelle->copy()->addMonth();

        // 3. Renvoi vers la BONNE vue
        // Le dossier est 'calendrierreservation' et le fichier 'showdispocalendrier'
        return view('calendrierreservation.showdispocalendrier', compact('annonce', 'dateActuelle', 'datePrecedente', 'dateSuivante'));
    }
public function showpaiement(Request $request, $id)
{
    // 1. Récupérer l'annonce
    $annonce = \App\Models\Annonce::findOrFail($id);

    return view('pagereservationpaiement.pagereservation', [
        'annonce' => $annonce
    ]);
}
}