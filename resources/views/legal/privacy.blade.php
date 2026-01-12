@extends('layouts.app')

@section('title', 'Politique de Confidentialité')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-200">
            <h1 class="text-3xl font-extrabold text-[#1f2d3d] mb-4 font-sans">Politique de Confidentialité</h1>
            <p class="text-gray-500 text-sm mb-8">Dernière mise à jour : {{ date('d/m/Y') }}</p>

            <div class="prose prose-orange max-w-none text-gray-700 space-y-6">
                
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">1. Introduction</h2>
                    <p>
                        Bienvenue sur <strong>Leboncoin</strong>. La protection de vos données personnelles est au cœur de nos préoccupations. 
                        Cette politique vise à vous informer en toute transparence sur les données que nous collectons, l'utilisation que nous en faisons et les droits dont vous disposez.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">2. Les données que nous collectons</h2>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Données d'identité :</strong> Nom, prénom, civilité, date de naissance.</li>
                        <li><strong>Données de contact :</strong> Adresse email, numéro de téléphone, adresse postale.</li>
                        <li><strong>Données de transaction :</strong> Historique des réservations, annonces publiées, montants réglés (nous ne stockons pas vos numéros de carte bancaire en clair).</li>
                        <li><strong>Documents officiels :</strong> Copie de votre pièce d'identité (CNI) pour la vérification des comptes vendeurs (stockée de manière sécurisée).</li>
                        <li><strong>Données techniques :</strong> Cookies, logs de connexion, adresse IP (à des fins de sécurité).</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">3. Pourquoi nous collectons ces données</h2>
                    <p>Vos données sont traitées pour les finalités suivantes :</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Gestion de votre compte utilisateur et de votre profil.</li>
                        <li>Publication et gestion de vos annonces de location.</li>
                        <li>Traitement des réservations et des paiements.</li>
                        <li>Vérification de l'identité pour garantir la sécurité des transactions (Lutte contre la fraude).</li>
                        <li>Gestion des litiges et du service client.</li>
                        <li>Envoi d'informations sur nos services (avec votre consentement).</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">4. Vos droits (RGPD)</h2>
                    <p>Conformément au Règlement Général sur la Protection des Données, vous disposez des droits suivants :</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Droit d'accès :</strong> Vous pouvez consulter l'ensemble des données vous concernant depuis votre espace personnel.</li>
                        <li><strong>Droit de rectification :</strong> Vous pouvez modifier vos informations à tout moment.</li>
                        <li><strong>Droit à l'effacement (Droit à l'oubli) :</strong> Vous pouvez demander la suppression définitive de votre compte et l'anonymisation de vos données.</li>
                        <li><strong>Droit à la portabilité :</strong> Vous pouvez télécharger vos données au format JSON.</li>
                    </ul>
                    
                    <div class="mt-4 bg-blue-50 p-4 rounded-lg border-l-4 border-lbc-blue">
                        <p class="text-sm text-blue-800">
                            <strong>Pour exercer ces droits :</strong> Rendez-vous dans votre espace membre, rubrique 
                            <a href="{{ route('profil.privacy') }}" class="font-bold underline hover:text-blue-600">Vie privée & Données</a>.
                        </p>
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">5. Cookies</h2>
                    <p>
                        Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences à tout moment via le bandeau dédié ou les paramètres de votre navigateur.
                        <br>
                        Les données de consentement sont conservées pour une durée de 6 mois.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">6. Contact DPO</h2>
                    <p>
                        Pour toute question relative à vos données personnelles, vous pouvez contacter notre Délégué à la Protection des Données (DPO) :
                    </p>
                    <p class="mt-2 font-medium">
                        Email : <a href="mailto:dpo@leoncoin.fr" class="text-lbc-orange hover:underline">dpo@leoncoin.fr</a><br>
                        Adresse : Service Juridique Leboncoin, 9 rue d'arc en ciel, 74000 Annecy, France
                    </p>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection