<?php

namespace App\Http\Controllers;


use App\Models\Professionnel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Routing\Controller;

class ProfessionnelController extends Controller
{
    public function siretLookup(Request $request)
    {
        $siret = (string) preg_replace('/\D/', '', $request->input('siret'));

        if (empty($siret) || strlen($siret) !== 14) {
            return response()->json(['error' => 'SIRET invalide ou manquant.'], 400);
        }

        try {
            
            $queryBuilder = DB::table('professionnel as p')
                
                ->leftJoin('adresse as a', 'p.idadresse', '=', 'a.idadresse')
                
                ->leftJoin('ville as v', 'a.idville', '=', 'v.idville')
                
                
                ->where(DB::raw('TRIM(p.numerosiret)'), $siret)
                
                
                ->select([
                    'p.nomprofessionnel',
                    'a.nomrue', 
                    'a.voie', 
                    'v.cpville', 
                    'v.nomville', 
                ]);
            
            
            $company = $queryBuilder->first(); 

            
            if (!$company) { 
                 return response()->json([
                     'message' => 'Aucune information trouvée dans notre base de données interne pour ce SIRET. Sorry'
                 ], 404);
            }
            
            
            return response()->json([
                'company_name' => $company->nomprofessionnel,
                
                
                'address'      => trim(($company->voie . ' ' . $company->nomrue) ?? ''), 
                
                
                'post_code'    => $company->cpville, 
                
                'city'         => $company->nomville, 
            ], 200);

        } catch (\Exception $e) {
            
            \Log::error("Erreur fatale de BDD lors de la recherche SIRET: " . $e->getMessage());
            return response()->json(['error' => 'Erreur critique du serveur lors de la recherche BDD.'], 500);
        }
    }
    
    public function show(string $id)
    { $professionnel = Professionnel::find($id);
        return view('professionnels.show', compact('professionnel'));
    }
}