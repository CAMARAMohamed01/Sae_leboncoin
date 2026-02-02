<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function send(Request $request)
    {
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10',
            'file-upload' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $filePath = null;

        
        if ($request->hasFile('file-upload')) {
            
            $filePath = $request->file('file-upload')->store('temp');
            $fullPath = storage_path('app/' . $filePath);
        }

        
        Mail::to('martin.nosland@univ-smb.fr')->send(new ContactRequest($validated, $fullPath ?? null));

        
        if(isset($fullPath) && file_exists($fullPath)) {
            unlink($fullPath);
        }

        
        return back()->with('success', 'Votre message a bien été envoyé à nos équipes !');
    }
}