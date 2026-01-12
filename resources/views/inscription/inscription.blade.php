@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)]">
    <div class="bg-white p-8 w-full max-w-lg mt-8">
        
        <h3 class="text-2xl font-extrabold text-[#1f2d3d] mb-4 font-sans">Créez un compte</h3>
        
        <p class="text-gray-600 text-base mb-10 leading-relaxed">
            Bénéficiez d’une expérience personnalisée avec du contenu en lien avec votre activité et vos centres d’intérêt sur notre service.
        </p>

        <div class="space-y-6">
            
            <div class="flex items-center">
                
                <a href="/perso" class="group flex items-center cursor-pointer no-underline">
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 transition-colors duration-200 group-hover:border-lbc-orange">
                        <div class="w-3 h-3 rounded-full bg-lbc-orange opacity-0 transform scale-0 transition-all duration-200 group-hover:opacity-100 group-hover:scale-100"></div>
                    </div>
                    
                    <span class="text-lg text-[#1f2d3d] font-medium group-hover:text-black">
                        Pour vous
                    </span>
                </a>

                <div class="group relative inline-block ml-3 align-middle">
                    <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                    
                    <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                        Compte personnel pour vos achats et ventes privés.
                    </div>
                </div>

            </div>
            <a href="/entreprise" class="group flex items-center cursor-pointer no-underline">
                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 transition-colors duration-200 group-hover:border-lbc-orange">
                    <div class="w-3 h-3 rounded-full bg-lbc-orange opacity-0 transform scale-0 transition-all duration-200 group-hover:opacity-100 group-hover:scale-100"></div>
                </div>
                
                <span class="text-lg text-[#1f2d3d] font-medium group-hover:text-black">
                    Pour votre entreprise
                </span>
            </a>
            <div class="group relative inline-block ml-3 align-middle">
                    <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                    
                    <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                        <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                        Compte réservé aux professionnels (sociétés, artisans, auto-entrepreneurs) disposant d'un numéro SIRET.
                    </div>
                </div>
        </div>

        <div class="mt-10 text-base font-medium text-[#1f2d3d]">
            Vous avez déjà un compte ? <a href="/login" class="underline font-bold hover:text-lbc-orange transition-colors">Me connecter</a>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-200 text-xs text-gray-500 leading-relaxed">
            À défaut, en application de l'article L 132-2 du Code de la consommation qui sanctionne les pratiques commerciales trompeuses, vous encourez une peine d'emprisonnement de 2 ans et une amende de 300 000 euros.
        </div>

    </div>
</div>
@endsection