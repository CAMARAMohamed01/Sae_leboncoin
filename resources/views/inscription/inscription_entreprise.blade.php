@extends('layouts.app')

@section('title', 'Inscription - Entreprise')

@section('content')
<div class="flex justify-center items-start min-h-[calc(100vh-200px)]">
    <div class="bg-white p-8 w-full max-w-md mt-8 rounded-lg shadow-sm border border-gray-200">
        
        <h3 class="text-2xl font-extrabold text-[#1f2d3d] mb-4 font-sans">
            Commençons par votre entreprise
        </h3>
        
        <p class="text-gray-600 text-base mb-8 leading-relaxed">
            Saisissez votre numéro de SIRET pour remplir automatiquement les coordonnées de votre entreprise.
        </p>

        <form action="{{ route('inscription.entreprise.siret') }}" method="GET" class="space-y-6">
            
        <div class="form-group">
            <label for="siret_search" class="block text-sm font-bold text-[#1f2d3d] mb-2">
                SIRET <span class="text-lbc-blue">*</span>
                <div class="group relative inline-block ml-2 align-middle">
                    <div class="cursor-help flex items-center justify-center w-5 h-5 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors duration-200">?</div>
                        <div class="invisible group-hover:visible absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-gray-900 text-white text-xs rounded-lg py-3 px-4 shadow-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none text-center leading-relaxed">
                            Numero de 14 chifre au format 123 123 123 12345 présent en haut des buletin de paie de votre entreprise
                        </div>
                    </div>
            </label>
            <input 
                type="text" 
                id="siret_search" 
                name="siret" 
                value="" 
                required 
                autofocus
                placeholder="Ex: 123 456 789 00012"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 text-gray-900 focus:outline-none focus:border-lbc-blue focus:ring-1 focus:ring-lbc-blue transition-colors duration-200"
                pattern="^\d{3} ?\d{3} ?\d{3} ?\d{5}$"
                inputmode="numeric"
                title="Le SIRET doit contenir exactement 14 chiffres"
            >
        </div>

            <button 
                type="submit"
                class="w-full bg-lbc-blue hover:bg-lbc-blue_hover text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-sm flex justify-center items-center gap-2"
            >
                Continuer
                <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>

        </form>

        <p class="mt-6 text-xs text-gray-400 text-center">
            * Champs requis
        </p>

    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const siretInput = document.getElementById('siret_search');

    siretInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); 
        
        value = value.substring(0, 14);

        let formattedValue = '';
        
        if (value.length > 0) {
            formattedValue += value.substring(0, 3);
        }
        if (value.length > 3) {
            formattedValue += ' ' + value.substring(3, 6);
        }
        if (value.length > 6) {
            formattedValue += ' ' + value.substring(6, 9);
        }
        if (value.length > 9) {
            formattedValue += ' ' + value.substring(9, 14);
        }

        e.target.value = formattedValue;
    });
});
</script>