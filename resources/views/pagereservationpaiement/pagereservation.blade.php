@extends('layouts.app')

@section('content')

{{-- Conteneur principal pour centrer le contenu comme sur LBC --}}
<div class="reservation-container">

    <h1>Vous voulez réserver l'annonce : {{ $annonce->titreannonce }}</h1>

    @php
        // Récupération sécurisée du prix unitaire (premier prix trouvé ou 0)
        $prixUnitaire = $annonce->prixPeriodes->first() ? $annonce->prixPeriodes->first()->prix : 0;
    @endphp

    {{-- Affichage du prix unitaire --}}
    <p class="text-lbc-orange font-bold mt-1" id="prixloc">{{ $prixUnitaire }} € / nuit</p>

    {{-- Inputs de dates --}}
    <p>Date d'arrivée</p>
    <input type="date" id="inputdatearriver" name="inputdatearriver" class="dateenter">

    <p>Date de départ</p>
    <input type="date" id="inputdatedepart" name="inputdatedepart" class="dateenter">

    {{-- Zone d'affichage du résultat ou des erreurs --}}
    <p id="presultat"></p>

    {{-- Input caché pour stocker le prix unitaire pour le JS --}}
    <input type="hidden" value="{{ $prixUnitaire }}" id="prixinput">

    {{-- Le Formulaire --}}
    <form action="{{ route('payment.checkout') }}" method="POST" id="form-reservation">
        @csrf
        
        {{-- Bouton type="button" pour que le JS gère la validation avant l'envoi --}}
        <button type="button" id="btnrerservation">Réserver l'annonce</button>
        
        {{-- Input caché qui recevra le PRIX TOTAL calculé --}}
        <input type="hidden" name="prixfinal" id="prixfinalid">
    </form>

    {{-- Message de succès/annulation si retour de paiement --}}
    @if(session('cancel'))
        <div class="alert alert-success">
            {{ session('cancel') }}
        </div>
    @endif

</div>

{{-- 
   ==========================================================================
   SECTION CSS (Style Le Bon Coin)
   ==========================================================================
--}}
<style>
    /* Conteneur pour centrer et limiter la largeur */
    .reservation-container {
        font-family: 'Nunito Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #1a1a1a;
        max-width: 600px;
        margin: 0 auto; /* Centré horizontalement */
        padding: 20px;
        background-color: #fff;
    }

    /* Titre */
    h1 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1a1a1a;
        line-height: 1.3;
    }

    /* Prix affiché */
    #prixloc {
        color: #ec5a13; /* Orange LBC */
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        display: inline-block;
        background-color: #fff0e9;
        padding: 4px 12px;
        border-radius: 8px;
    }

    /* Labels des dates */
    p {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        margin-top: 16px;
        color: #4a4a4a;
    }

    /* Champs de date */
    .dateenter {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 16px;
        font-size: 16px;
        border: 1px solid #cad1d9;
        border-radius: 12px;
        background-color: #ffffff;
        color: #1a1a1a;
        transition: all 0.2s ease-in-out;
        outline: none;
        min-height: 48px;
        font-family: inherit;
    }

    .dateenter:focus {
        border-color: #ec5a13;
        box-shadow: 0 0 0 4px rgba(236, 90, 19, 0.15);
    }

    /* Résultat (Prix total ou erreur) */
    #presultat {
        margin-top: 20px;
        padding: 10px;
        min-height: 24px;
        font-size: 18px;
        font-weight: bold;
    }

    /* Bouton Réserver */
    #btnrerservation {
        display: block;
        width: 100%;
        margin-top: 24px;
        background-color: #ec5a13;
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        padding: 14px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
        box-shadow: 0 4px 12px rgba(236, 90, 19, 0.2);
    }

    #btnrerservation:hover {
        background-color: #d64d0d;
    }

    #btnrerservation:active {
        transform: scale(0.98);
    }

    /* Alertes */
    .alert-success {
        background-color: #e5f6fd;
        color: #016496;
        padding: 12px;
        border-radius: 8px;
        margin-top: 20px;
        border: 1px solid #0288d1;
    }
</style>

{{-- 
   ==========================================================================
   SECTION JAVASCRIPT (Logique de calcul)
   ==========================================================================
--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sélection des éléments HTML
        const prixfinalp = document.querySelector("#presultat");
        const nnb = document.querySelector("#inputdatearriver");
        const nnb2 = document.querySelector("#inputdatedepart");
        const inputsDates = document.querySelectorAll(".dateenter");
        
        // Conversion du prix unitaire en nombre (float) pour les calculs
        const prixInputVal = document.querySelector("#prixinput").value;
        const prixfixe = prixInputVal ? parseFloat(prixInputVal) : 0;

        const btnreserver = document.querySelector("#btnrerservation");
        const formReservation = document.querySelector("#form-reservation");
        const inputPriceFinal = document.querySelector("#prixfinalid");

        // Fonction pour formater la date en YYYY-MM-DD
        function formaterDate(date) {
            const annee = date.getFullYear();
            const mois = String(date.getMonth() + 1).padStart(2, '0');
            const jour = String(date.getDate()).padStart(2, '0');
            return `${annee}-${mois}-${jour}`;
        }

        // Configuration des dates min/max (aujourd'hui -> +2 ans)
        inputsDates.forEach(element => {
            if(element) {
                const aujourdhui = new Date();
                element.min = formaterDate(aujourdhui); 
                
                const dateDansDeuxAns = new Date(aujourdhui);
                dateDansDeuxAns.setFullYear(aujourdhui.getFullYear() + 2);
                element.max = formaterDate(dateDansDeuxAns);
                
                // Recalculer le prix à chaque changement de date
                element.addEventListener('change', calculPrix);
            }
        });

        // Fonction principale de calcul et validation
        function calculPrix() {
            prixfinalp.innerText = ""; // Réinitialiser le message

            // 1. Vérification si les champs sont remplis
            if(!nnb.value) {
                prixfinalp.innerText = "Veuillez choisir une date d'arrivée.";
                prixfinalp.style.color = "#cc3300"; // Rouge erreur
                return false;
            }

            // --- NOUVELLE VERIFICATION ICI : Date arrivée < Aujourd'hui ---
            const dateArriveeCheck = new Date(nnb.value);
            const dateAujourdhuiCheck = new Date();
            // On remet l'heure à 00:00:00 pour comparer uniquement les jours
            dateAujourdhuiCheck.setHours(0,0,0,0); 

            if(dateArriveeCheck < dateAujourdhuiCheck) {
                prixfinalp.innerText = "La date d'arrivée ne peut pas être inférieure à aujourd'hui.";
                prixfinalp.style.color = "#cc3300";
                return false;
            }
            // -------------------------------------------------------------

            if(!nnb2.value) {
                prixfinalp.innerText = "Veuillez choisir une date de départ.";
                prixfinalp.style.color = "#cc3300";
                return false;
            }

            // 2. Vérification de la logique des dates
            if (nnb2.value <= nnb.value){
                prixfinalp.innerText = "La date de départ doit être après la date d'arrivée.";
                prixfinalp.style.color = "#cc3300";
                return false;
            }

            // 3. Calcul du prix
            const date1 = new Date(nnb.value);
            const date2 = new Date(nnb2.value);
            
            // Différence en millisecondes convertie en jours
            const differenceTemps = date2 - date1;
            const jours = differenceTemps / (1000 * 3600 * 24);
            
            const total = prixfixe * jours;
            
            // 4. Affichage et mise à jour de l'input caché
            // Formatage du prix en français (ex: 120,50 €)
            const totalFormatte = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(total);
            
            prixfinalp.innerText = "Prix total pour " + jours + " nuit(s) : " + totalFormatte;
            prixfinalp.style.color = "#188a42"; // Vert succès
            
            inputPriceFinal.value = total; // Valeur envoyée au serveur
            
            return true;
        }

        // Gestion du click sur le bouton "Réserver"
        btnreserver.addEventListener("click", function(event) {
            event.preventDefault(); // On empêche l'envoi immédiat
            
            // On lance le calcul. Si tout est valide (renvoie true)...
            if(calculPrix()) {
                // ... on soumet le formulaire manuellement
                formReservation.submit();
            } else {
                // Sinon, on affiche une animation ou une erreur visuelle (optionnel)
                console.log("Erreur de validation");
            }
        });
    });
</script>
@endsection