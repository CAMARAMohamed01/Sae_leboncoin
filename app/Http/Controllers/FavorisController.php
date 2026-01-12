<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Annonce;

class FavorisController extends Controller
{

    public function toggle($id)
    {
        $user = Auth::user();

        $user->favoris()->toggle($id);

        return back(); 
    }
}