@extends('layouts.app')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8 max-w-5xl mx-auto">
        
        <!-- SIDEBAR GAUCHE (Menu & Résumé) -->
        <div id="profil-sidebar" class="w-full md:w-1/3 lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                
                <div class="flex flex-col items-center text-center mb-6">
                    <!-- AVATAR DYNAMIQUE (Sidebar) -->
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100 flex items-center justify-center text-3xl text-gray-400 font-bold uppercase mb-3 relative group">
                        @if($user->photo)
                            <!-- Affichage de l'image stockée -->
                            <img src="{{ asset($user->photo->lienurl) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <!-- Affichage des initiales par défaut -->
                            {{ substr($user->particulier->prenomparticulier ?? ($user->professionnel->nomprofessionnel ?? 'U'), 0, 1) }}
                        @endif
                    </div>

                    <div class="overflow-hidden w-full">
                        <p class="font-bold text-gray-900 truncate text-lg">
                            {{ $user->particulier->prenomparticulier ?? '' }} 
                            {{ $user->particulier->nomparticulier ?? ($user->professionnel->nomprofessionnel ?? 'Utilisateur') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $user->particulier ? 'Compte Particulier' : 'Compte Professionnel' }}
                        </p>
                        @if($user->role != 'utilisateur normal')
                            <p class="my-2 text-xs text-red-500"> {{$user->role}}</p>
                        @endif
                    </div>
                </div>
                
                <nav class="space-y-1 text-sm font-medium">
                    <a href="{{ route('profil.edit') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-lbc-blue rounded-lg transition border-l-4 border-lbc-blue">
                        <i class="fa-regular fa-user"></i> Mes informations
                    </a>
                     <a href="{{ route('profil.privacy') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-solid text-orange-600 fa-shield-halved"></i> Vie privée & Données
                    </a>
                    <a href="{{ route('annonces.mes_annonces') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-solid text-orange-600 fa-list"></i> Mes annonces
                    </a>
                    <a href="{{ route('proprietaire.demandes') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-regular text-orange-600 fa-bell"></i> Demandes reçues
                    </a>
                    <a href="{{ route('annonces.mes_favoris') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-regular text-orange-600 fa-heart"></i> Mes favoris
                    </a>
                    <a href="{{ route('reservations.mes_locations') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-solid text-orange-600 fa-house"></i></i> Mes locations
                    </a>
                    <a href="{{route('gereincident.gererincident', ['id' => Auth::id()])}}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                    <i class="fa-solid text-orange-600 fa-question"></i> gerer les incidents
                    </a>
                    <a href="{{route('gereplainte.gererplainte', ['id' => Auth::id()])}}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                    <i class="fa-solid text-orange-600 fa-triangle-exclamation"></i> gerer mes plaintes
                    </a>        
                    @if($user->role == 'Directeur')
                        <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Administration</p>
                        </div>
                        <a href="{{ route('comparatif.comparatif') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                            <i class="fa-solid text-orange-600 fa-chart-pie"></i> Admin / Comparatif
                        </a>
                    @endif
                    @if($user->role == 'service inscription')
                    <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Gestion d'inscriptions</p>
                    </div>
                        <a href="{{ route('admin.inscriptions') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-user-check text-orange-600 w-5"></i> Demandes Inscriptions
                        </a>
                    @endif
                    @if($user->role == 'service location')
                    <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Gestion des incidents</p>
                        <a href="{{ route('admin.location.incidents') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                            <i class="fa-solid text-orange-600 fa-chart-pie"></i> Gérer les incidents
                        </a>
                    @endif
                    @if($user->role == 'service immobilier')
                        <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Expertise</p>
                        </div>
                        <a href="{{ route('admin.immobilier.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid text-orange-600 fa-chart-pie"></i> Avis Expert
                        </a>
                    @endif
        
                    @if($user->role == 'service petit annonce')
                            <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Configuration</p>
                            </div> 
                        
                        
                        <a href="{{ route('admin.annonces.equipements.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-plug text-orange-500 w-5"></i> 
                            Gérer les Équipements
                        </a>
                        
                        <a href="{{ route('admin.annonces.types.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-hotel text-orange-500 w-5"></i> 
                            Gérer Types Hébergement
                        </a>
                    @endif
                    @if($user->isServiceJuridique())
                        @if(!$user->isServiceInscription() && !$user->isServiceLocation() && !$user->isServiceImmobilier()) 
                            <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Juridique</p>
                            </div> 
                        @endif
                        <a href="{{ route('admin.juridique.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-scale-balanced text-orange-600 w-5"></i> 
                            Conformité Cookies
                        </a>
                    @endif


                    {{--  Délégué Protection Données (DPO) --}}
                    @if($user->isDPO())
                        @if(!$user->isServiceInscription() && !$user->isServiceLocation() && !$user->isServiceImmobilier() && !$user->isServiceAnnonce()) 
                            <div class="mt-4 pt-2 border-t border-gray-100"><p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Légal & Sécurité</p></div> 
                        @endif
                        <a href="{{ route('admin.rgpd.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-user-shield text-orange-500 w-5 hover:text-white"></i> 
                            Espace DPO / RGPD
                        </a>
                        <a href="{{ route('admin.rgpd.demandes') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-user-xmark text-orange-600 w-5"></i> 
                            Demandes Suppression
                        </a>
                    @endif
                    
                    <!-- {{-- Bouton de déconnexion --}} -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-6 pt-6 border-t border-gray-100">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition font-semibold">
                            <i class="fa-solid fa-power-off"></i> Se déconnecter
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- FORMULAIRE PRINCIPAL (Droite) -->
        <div class="w-full md:w-2/3 lg:w-3/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-8 font-sans border-b border-gray-100 pb-4">
                    Mes informations personnelles
                </h1>

                <!-- Message de succès -->
                @if(session('success'))
                    <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Message d'erreur global -->
                @if($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORMULAIRE (Note le 'enctype' obligatoire pour l'upload) -->
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- SECTION 0 : PHOTO DE PROFIL -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-dashed border-gray-300">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Photo de profil</h2>
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            <div class="flex-shrink-0">
                                @if($user->photo)
                                    <img class="h-20 w-20 rounded-full object-cover border-2 border-white shadow-sm" src="{{ asset($user->photo->lienurl) }}" alt="Votre photo">
                                @else
                                    <span class="h-20 w-20 rounded-full overflow-hidden bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-camera text-gray-300 text-3xl"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Modifier votre photo</label>
                                <input type="file" name="avatar" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-orange-50 file:text-lbc-orange
                                    hover:file:bg-orange-100
                                    cursor-pointer border border-gray-300 rounded-lg p-1 bg-white
                                ">
                                <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, PNG ou GIF (Max. 2Mo).</p>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 1 : IDENTITÉ (Conditionnel) -->
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Identité</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($user->particulier)
                                {{-- Champs Particulier --}}
                                <div class="md:col-span-2 flex gap-6">
                                    <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 w-full sm:w-auto">
                                        <input type="radio" name="civilite" value="MR" class="text-lbc-orange focus:ring-lbc-orange" {{ old('civilite', $user->particulier->civilite) == 'MR' ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700 font-medium">Monsieur</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 w-full sm:w-auto">
                                        <input type="radio" name="civilite" value="ME" class="text-lbc-orange focus:ring-lbc-orange" {{ old('civilite', $user->particulier->civilite) == 'ME' ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700 font-medium">Madame</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Prénom</label>
                                    <input type="text" name="prenom" value="{{ old('prenom', $user->particulier->prenomparticulier) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nom</label>
                                    <input type="text" name="nom" value="{{ old('nom', $user->particulier->nomparticulier) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors">
                                </div>

                            @elseif($user->professionnel)
                                {{-- Champs Professionnel --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Raison Sociale</label>
                                    <input type="text" name="nom" value="{{ old('nom', $user->professionnel->nomprofessionnel) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Numéro SIRET</label>
                                    <input type="text" name="siret" value="{{ old('siret', $user->professionnel->numerosiret) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange transition-colors" maxlength="14">
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Coordonnées & Adresse</h2>
                        
                        {{-- Email & Téléphone --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->emailutilisateur) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:border-lbc-orange">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone</label>
                                <input type="text" name="telutilisateur" value="{{ old('telutilisateur', $user->telutilisateur) }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" maxlength="10">
                            </div>
                        </div>

                        {{-- Adresse Postale Détaillée --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            
                            {{-- Ligne 1 : Numéro et Rue  --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">N°</label>
                                    <input type="text" id="numero_voie" name="numero_voie" value="{{ old('numero_voie', $user->adresse->voie ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                                </div>
                                <div class="md:col-span-3 relative">
                                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Rue / Voie</label>
                                    <input type="text" id="nom_rue" name="nom_rue" value="{{ old('nom_rue', $user->adresse->nomrue ?? '') }}" autocomplete="off" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange" placeholder="Commencez à taper votre adresse...">
                                    
                                    {{-- Liste de suggestions --}}
                                    <div id="address-suggestions" class="absolute z-50 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
                                </div>
                            </div>

                            {{-- Ligne 2 : CP et Ville --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Code Postal</label>
                                    <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal', $user->adresse->ville->cpville ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Ville</label>
                                    <input type="text" id="nom_ville" name="nom_ville" value="{{ old('nom_ville', $user->adresse->ville->nomville ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-lbc-orange focus:ring-1 focus:ring-lbc-orange">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider flex items-center gap-2">
                            Pièce d'identité
                            @if($user->identite)
                                <span class="bg-green-100 text-green-800 text-[10px] px-2 py-0.5 rounded-full border border-green-200 font-bold normal-case">Document fourni</span>
                            @endif
                        </h2>
                        
                        <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100 flex gap-3">
                            <div class="shrink-0 text-blue-600 mt-1"><i class="fa-solid fa-shield-halved"></i></div>
                            <div class="text-sm text-blue-800">
                                <p class="font-bold mb-1">Espace de confiance</p>
                                <p class="text-xs opacity-90">Votre pièce d'identité permet de certifier votre profil et de rassurer les autres membres.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Numéro de la pièce</label>
                                <input type="text" name="cni_numero" value="{{ old('cni_numero', $user->identite->numeroidentite ?? '') }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:border-lbc-orange" placeholder="Ex: 123456789">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Fichier (Recto/Verso)
                                    @if($user->identite && $user->identite->lien_document)
                                        <a href="{{ asset($user->identite->lien_document) }}" target="_blank" class="text-xs text-lbc-blue hover:underline ml-2 font-normal">
                                            <i class="fa-solid fa-eye"></i> Voir le document actuel
                                        </a>
                                    @endif
                                </label>
                                <input type="file" name="cni_file" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-3 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-orange-50 file:text-lbc-orange
                                    hover:file:bg-orange-100
                                    cursor-pointer border border-gray-300 rounded-lg bg-white
                                ">
                                <p class="mt-1 text-xs text-gray-500">PDF, JPG ou PNG (Max 4Mo). Laissez vide pour conserver l'actuel.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">
                    <!--  MOT DE PASSE -->
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Mot de passe</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                <input type="password" name="password" class="w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3 : PORTEFEUILLE / PROPRIÉTAIRE -->
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Portefeuille & Infos</h2>
                        <div class="flex items-center gap-4 bg-orange-50 p-4 rounded-lg border border-orange-100">
                            <div class="bg-white p-3 rounded-full text-lbc-orange shadow-sm">
                                <i class="fa-solid fa-wallet text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-medium">Solde actuel</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($user->solde, 2, ',', ' ') }} €</p>
                            </div>
                            <div class="ml-auto">
                                <span class="text-xs bg-white text-gray-500 px-3 py-1 rounded-full border border-gray-200">
                                    Géré par Leboncoin
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- BOUTON ENREGISTRER -->
                    <div class="flex justify-end pt-6 border-t border-gray-100 mt-4">
                        <button type="submit" class="bg-lbc-orange hover:bg-[#d64d0e] text-white font-bold py-3 px-8 rounded-full shadow-md transition transform active:scale-95 flex items-center gap-2">
                            <i class="fa-solid fa-check"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- AUTOCOMPLÉTION ADRESSE INTELLIGENTE ---
        const streetInput = document.getElementById('nom_rue');
        const numberInput = document.getElementById('numero_voie');
        const zipInput = document.getElementById('code_postal');
        const cityInput = document.getElementById('nom_ville');
        const suggestionsBox = document.getElementById('address-suggestions');
        let timeout = null;

        if (streetInput) {
            streetInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;

                if (query.length > 3) {
                    timeout = setTimeout(() => {
                        // On cherche avec "q" pour avoir l'adresse complète
                        fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5&autocomplete=1`)
                            .then(response => response.json())
                            .then(data => {
                                suggestionsBox.innerHTML = '';
                                if (data.features && data.features.length > 0) {
                                    suggestionsBox.classList.remove('hidden');
                                    
                                    data.features.forEach(feature => {
                                        const div = document.createElement('div');
                                        div.className = 'px-4 py-2 cursor-pointer hover:bg-orange-50 text-sm text-gray-700 border-b border-gray-100 last:border-0';
                                        div.textContent = feature.properties.label;
                                        
                                        div.addEventListener('click', function() {
                                            // Remplissage intelligent des champs
                                            if (feature.properties.housenumber) {
                                                numberInput.value = feature.properties.housenumber;
                                            }
                                            streetInput.value = feature.properties.street || feature.properties.name;
                                            zipInput.value = feature.properties.postcode;
                                            cityInput.value = feature.properties.city;

                                            suggestionsBox.classList.add('hidden');
                                        });
                                        
                                        suggestionsBox.appendChild(div);
                                    });
                                } else {
                                    suggestionsBox.classList.add('hidden');
                                }
                            });
                    }, 300);
                } else {
                    suggestionsBox.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!streetInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection