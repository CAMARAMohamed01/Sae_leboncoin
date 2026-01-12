<?php

namespace App\Http\Controllers;
use App\Models\Particulier;
use Illuminate\Http\Request;

class ParticulierController extends Controller
{
    public function show(string $id)
{
    $proprietaireperso = Particulier::findOrFail($id);
    
    return view('particuliers.show', compact('proprietaireperso')); 
}
}