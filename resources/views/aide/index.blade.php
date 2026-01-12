@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen pb-12 font-sans">
        
        {{-- En-tête / Recherche --}}
        <div class="bg-orange-600 py-16 px-4 sm:px-6 lg:px-8 shadow-sm">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl font-bold text-white sm:text-4xl tracking-tight">
                    Bonjour, comment pouvons-nous vous aider ?
                </h1>
                <p class="mt-4 text-orange-100 text-lg">
                    Parcourez les questions fréquentes ou effectuez une recherche.
                </p>
                
                {{-- Barre de recherche FORM --}}
<div class="mt-8 relative max-w-xl mx-auto group">
    <form action="{{ route('aide.search') }}" method="GET">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" 
               name="q" 
               required
               class="block w-full pl-11 pr-4 py-4 border-0 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-lg text-sm transition-shadow duration-200 ease-in-out" 
               placeholder="Rechercher une réponse (ex: mot de passe, annonce...)">
        
        {{-- Bouton caché pour valider avec "Entrée" --}}
        <button type="submit" class="hidden">Rechercher</button>
    </form>
</div>
            </div>
        </div>

        {{-- Contenu Principal --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            
            {{-- Grille des Thématiques --}}
            {{-- ... Ton code existant ... --}}

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
    
    {{-- Card 1: Compte (Modifiée en lien) --}}
    <a href="{{ route('aide.show', 'compte') }}" class="block group">
        <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-orange-500 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full">
            <div class="flex items-center justify-center h-14 w-14 rounded-full bg-orange-50 text-orange-600 mb-6 mx-auto group-hover:bg-orange-100 transition">
                {{-- Ton icone SVG --}}
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-3">Mon Compte</h3>
            <p class="text-gray-500 text-center text-sm leading-relaxed">Gestion du profil, connexion et paramètres personnels.</p>
        </div>
    </a>

    {{-- Card 2: Annonces (Modifiée en lien) --}}
    <a href="{{ route('aide.show', 'annonces') }}" class="block group">
        <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-blue-500 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full">
             <div class="flex items-center justify-center h-14 w-14 rounded-full bg-blue-50 text-blue-600 mb-6 mx-auto group-hover:bg-blue-100 transition">
                {{-- Ton icone SVG --}}
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-3">Dépôt & Gestion</h3>
            <p class="text-gray-500 text-center text-sm leading-relaxed">Publier une annonce, modifier les prix et photos.</p>
        </div>
    </a>

    {{-- Card 3: Sécurité (Modifiée en lien) --}}
    <a href="{{ route('aide.show', 'securite') }}" class="block group">
        <div class="bg-white rounded-xl shadow-md p-6 border-b-4 border-green-500 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full">
             <div class="flex items-center justify-center h-14 w-14 rounded-full bg-green-50 text-green-600 mb-6 mx-auto group-hover:bg-green-100 transition">
                {{-- Ton icone SVG --}}
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-3">Sécurité</h3>
            <p class="text-gray-500 text-center text-sm leading-relaxed">Conseils de vigilance, paiements et signalements.</p>
        </div>
    </a>

</div>

            {{-- Section FAQ --}}
            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b pb-4">Questions Fréquentes</h2>

                {{-- Bloc FAQ 1 --}}
                <div class="mb-10">
                    <h3 class="flex items-center text-lg font-semibold text-gray-900 mb-5">
                        <span class="w-1 h-6 bg-orange-500 rounded-full mr-3"></span>
                        Gestion de mon compte
                    </h3>
                    <div class="space-y-4">
                        
                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Comment créer un compte ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Pour créer un compte, cliquez sur le bouton <strong>"Se connecter"</strong> en haut à droite de la page d'accueil, puis sélectionnez l'option d'inscription. Remplissez le formulaire avec vos informations personnelles et validez votre email.
                            </div>
                        </details>

                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">J'ai oublié mon mot de passe</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Rendez-vous sur la page de connexion et cliquez sur le lien <strong>"Mot de passe oublié"</strong>. Saisissez l'adresse email associée à votre compte pour recevoir un lien de réinitialisation sécurisé.
                            </div>
                        </details>

                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-orange-50 hover:text-orange-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Comment supprimer mon compte ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Vous pouvez supprimer votre compte depuis vos paramètres de profil. Attention, cette action est irréversible et entraînera la suppression de toutes vos annonces actives et de votre historique.
                            </div>
                        </details>
                    </div>
                </div>

                {{-- Bloc FAQ 2 --}}
                <div class="mb-10">
                    <h3 class="flex items-center text-lg font-semibold text-gray-900 mb-5">
                        <span class="w-1 h-6 bg-blue-500 rounded-full mr-3"></span>
                        Annonces et Tarification
                    </h3>
                    <div class="space-y-4">
                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Le dépôt d'annonce est-il payant ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Le dépôt d'annonce est <strong>100% gratuit</strong> pour les particuliers. Certaines options de mise en avant (ex: remonter en tête de liste) peuvent être payantes.
                            </div>
                        </details>

                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Pourquoi certains prix sont affichés "par nuit" ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Cet affichage concerne principalement la catégorie "Vacances". Si vous constatez cet affichage sur un objet à vendre, merci de vérifier la description de l'annonce ou de contacter le vendeur pour confirmation du prix de vente.
                            </div>
                        </details>
                    </div>
                </div>

                {{-- Bloc FAQ 3 --}}
                <div class="mb-4">
                    <h3 class="flex items-center text-lg font-semibold text-gray-900 mb-5">
                        <span class="w-1 h-6 bg-green-500 rounded-full mr-3"></span>
                        Confiance et Sécurité
                    </h3>
                    <div class="space-y-4">
                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-green-50 hover:text-green-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Comment éviter les tentatives de fraude ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                Privilégiez toujours la remise en main propre. Méfiez-vous des offres trop alléchantes et n'utilisez jamais de moyens de paiement anonymes (type Mandat Cash, Western Union) pour une transaction à distance.
                            </div>
                        </details>

                        <details class="group [&_summary::-webkit-details-marker]:hidden">
                            <summary class="flex items-center justify-between p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-green-50 hover:text-green-700 transition-colors duration-200">
                                <span class="font-medium text-gray-700">Que faire en cas de litige ?</span>
                                <span class="text-gray-500 group-open:rotate-180 transition-transform duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </summary>
                            <div class="mt-2 px-4 pb-4 text-gray-600 text-sm leading-relaxed">
                                En cas de désaccord avec un utilisateur, nous vous invitons d'abord à dialoguer. Si le problème persiste ou si vous suspectez un comportement illégal, utilisez le bouton "Signaler l'annonce" présent sur chaque page produit.
                            </div>
                        </details>
                    </div>
                </div>

            </div>

            {{-- Footer Support --}}
            <div class="mt-12 text-center pb-12">
                <p class="text-gray-500 mb-4">Vous ne trouvez pas la réponse à votre question ?</p>
                <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-orange-600 bg-orange-100 hover:bg-orange-200 md:text-lg transition-colors duration-200">
                    Contacter notre service client
                </a>
            </div>

        </div>
    </div>
@endsection