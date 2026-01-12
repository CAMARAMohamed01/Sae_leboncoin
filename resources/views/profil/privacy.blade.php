@extends('layouts.app')

@section('title', 'Vie privée & Données')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8 max-w-5xl mx-auto">
        
        <!-- SIDEBAR (Menu) -->
        <div class="w-full md:w-1/3 lg:w-1/4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100 flex items-center justify-center text-3xl text-gray-400 font-bold uppercase mb-3 relative group">
                        @if($user->photo)
                            <img src="{{ asset($user->photo->lienurl) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
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
                    </div>
                </div>
                
                <nav class="space-y-1 text-sm font-medium">
                    <a href="{{ route('profil.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition">
                        <i class="fa-regular text-orange-600 fa-user"></i> Mes informations
                    </a>

                    <a href="{{ route('profil.privacy') }}" class="flex items-center gap-3 px-4 py-2 bg-blue-50 text-lbc-blue rounded-lg transition border-l-4 border-lbc-blue">
                        <i class="fa-solid fa-shield-halved"></i> Vie privée & Données
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
                        <i class="fa-solid text-orange-600 fa-suitcase"></i> Mes locations
                    </a>
                    
                    {{-- BLOC ADMINISTRATION --}}
                    @if($user->isServiceInscription())
                        <div class="mt-4 pt-2 border-t border-gray-100">
                            <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Administration</p>
                            <a href="{{ route('admin.inscriptions') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition font-medium">
                                <i class="fa-solid fa-user-check text-blue-500 w-5"></i> 
                                Validations Inscriptions
                            </a>
                        </div>
                    @endif

                    @if($user->isServiceLocation())
                        @if(!$user->isServiceInscription()) 
                            <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Service Location</p>
                            </div> 
                        @endif
                        <a href="{{ route('admin.location.incidents') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 w-5"></i> 
                            Gérer les Incidents
                        </a>
                    @endif

                    @if($user->isServiceImmobilier())
                        @if(!$user->isServiceInscription() && !$user->isServiceLocation()) 
                            <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Expertise</p>
                            </div> 
                        @endif
                        <a href="{{ route('admin.immobilier.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-house-chimney-medical text-purple-500 w-5"></i> 
                            Avis Experts
                        </a>
                    @endif

                    @if($user->isServiceAnnonce())
                        @if(!$user->isServiceInscription() && !$user->isServiceLocation() && !$user->isServiceImmobilier()) 
                            <div class="mt-4 pt-2 border-t border-gray-100">
                                <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Configuration</p>
                            </div> 
                        @endif
                        <a href="{{ route('admin.annonces.equipements.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-plug text-orange-500 w-5"></i> 
                            Gérer les Équipements
                        </a>
                        <a href="{{ route('admin.annonces.types.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-hotel text-orange-500 w-5"></i> 
                            Gérer Types Hébergement
                        </a>
                    @endif

                    @if($user->isDPO())
                        @if(!$user->isServiceInscription() && !$user->isServiceLocation() && !$user->isServiceImmobilier() && !$user->isServiceAnnonce()) 
                            <div class="mt-4 pt-2 border-t border-gray-100"><p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">Légal & Sécurité</p></div> 
                        @endif
                        <a href="{{ route('admin.rgpd.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-user-shield text-orange-600 w-5 hover:text-white"></i> 
                            Espace DPO / RGPD
                        </a>
                        <a href="{{ route('admin.rgpd.demandes') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition font-medium">
                            <i class="fa-solid fa-user-xmark text-orange-600 w-5"></i> 
                            Demandes Suppression
                        </a>
                    @endif
                    
                    {{-- Bouton de déconnexion --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-6 pt-4 border-t border-gray-100">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition font-semibold">
                            <i class="fa-solid fa-power-off"></i> Se déconnecter
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="w-full md:w-2/3 lg:w-3/4">
            <div class="space-y-6">

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- 1. Synthèse des données -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h1 class="text-2xl font-extrabold text-[#1f2d3d] mb-2 font-sans">
                        Mes données personnelles
                    </h1>
                    <p class="text-gray-500 text-sm mb-6">
                        Conformément au RGPD, vous avez un droit d'accès aux informations que nous conservons à votre sujet.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <h3 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fa-regular fa-id-card text-blue-500"></i> Identité & Contact
                            </h3>
                            <ul class="text-sm space-y-2 text-gray-600">
                                <li><strong>Nom :</strong> {{ $user->nom_affichage }}</li>
                                <li><strong>Email :</strong> {{ $user->emailutilisateur }}</li>
                                <li><strong>Téléphone :</strong> {{ $user->telutilisateur }}</li>
                                <li><strong>Inscrit le :</strong> {{ $user->date_creation ? $user->date_creation->format('d/m/Y') : 'N/A' }}</li>
                            </ul>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <h3 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-chart-simple text-blue-500"></i> Activité
                            </h3>
                            <ul class="text-sm space-y-2 text-gray-600">
                                <li><strong>Annonces publiées :</strong> {{ $user->annonces->count() }}</li>
                                <li><strong>Réservations :</strong> {{ $user->reservations->count() }}</li>
                                <li><strong>Favoris :</strong> {{ $user->favoris->count() }}</li>
                                <li><strong>Identité vérifiée :</strong> 
                                    @if($user->identite)
                                        <span class="text-green-600 font-bold">Oui</span> ({{ $user->identite->typeidentite }})
                                    @else
                                        <span class="text-gray-400">Non</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-2">Portabilité des données</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Vous pouvez télécharger une copie intégrale de vos données au format JSON pour les transférer vers un autre service.
                        </p>
                        <a href="{{ route('profil.export') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 font-bold py-2.5 px-4 rounded-lg hover:bg-gray-50 transition shadow-sm">
                            <i class="fa-solid fa-download"></i> Télécharger mes données
                        </a>
                    </div>
                </div>

                <!-- 2. Zone de danger (Suppression) -->
                <div class="bg-white rounded-xl shadow-sm border border-red-100 p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                    
                    <h2 class="text-xl font-bold text-red-700 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i> Suppression du compte
                    </h2>
                    <p class="text-gray-600 text-sm mb-6">
                        Vous pouvez demander la suppression définitive de votre compte et l'anonymisation de vos données (Droit à l'oubli).<br>
                        Cette action est <strong>irréversible</strong> et entraînera la perte de l'accès à vos annonces et réservations.
                    </p>

                    <form action="{{ route('profil.destroy') }}" method="POST" class="bg-red-50 p-6 rounded-lg border border-red-100">
                        @csrf
                        @method('DELETE')

                        <label class="block text-sm font-bold text-red-800 mb-2">
                            Pour confirmer votre demande, veuillez saisir votre mot de passe actuel :
                        </label>
                        
                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            <div class="flex-1 w-full">
                                <input type="password" name="password_delete" required class="w-full px-4 py-2.5 rounded-lg border border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white placeholder-red-200" placeholder="Votre mot de passe">
                                @error('password_delete')
                                    <p class="text-xs text-red-600 mt-1 font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition whitespace-nowrap flex items-center justify-center gap-2" onclick="return confirm('Êtes-vous absolument sûr ? Vos données seront effacées.');">
                                <i class="fa-solid fa-trash"></i> Demander la suppression
                            </button>
                        </div>
                        
                        <p class="text-[10px] text-red-400 mt-3 italic">
                            Votre demande sera transmise à notre Délégué à la Protection des Données (DPO) et traitée sous 30 jours.
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection