<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locataire;

class LocataireController extends Controller
{
    public function maFonction($id)
    {
        $locataire = Locataire::findOrFail($id);
        return view('annocesdejalouee.annoncelouee', compact('locataire'));
    }
}