{{-- BANDEAU COOKIES (RGPD) --}}
<div id="cookie-banner" 
     class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_20px_rgba(0,0,0,0.15)] border-t border-gray-200 z-[9999] hidden p-4 md:p-6 transition-transform duration-500 transform translate-y-full"
     style="z-index: 9999;">
    
    <div class="container mx-auto max-w-5xl flex flex-col md:flex-row items-center justify-between gap-4">
        
        {{-- Texte explicatif --}}
        <div class="flex-1 text-sm text-gray-600">
            <p class="font-bold text-gray-900 mb-1 flex items-center gap-2">
                <i class="fa-solid fa-cookie-bite text-lbc-orange"></i> Votre vie privée nous importe
            </p>
            <p>
                Nous utilisons des cookies pour personnaliser votre expérience et mesurer l'audience. 
                Vos choix sont anonymes et conservés pendant 6 mois.
                Vous pouvez à tout moment
                <!-- choisir les cookies collectés -->
                <a href="{{route('legal.privacy')}}" class="underline text-lbc-blue hover:text-blue-700">choisir les cookies collectés</a>
                et 
                <!-- consulter nos partenaires -->
                <a href="{{route('legal.privacy')}}" class="underline text-lbc-blue hover:text-blue-700">consulter nos partenaires</a>.
                Pour en savoir plus vous pouvez consulter notre 
                <!-- politique de confidentialité -->
                <a href="{{route('legal.privacy')}}" class="underline text-lbc-blue hover:text-blue-700">politique de confidentialité</a>.
            </p>
        </div>

        {{-- Boutons d'action --}}
        <div class="flex items-center gap-3 shrink-0 mt-2">
            <button id="cookie-refuse" type="button" class="cursor-pointer px-4 py-2 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition select-none border border-gray-300">
                Continuer sans accepter
            </button>
            <button id="cookie-accept" class="cursor-pointer relative z-10 px-6 py-2 text-sm font-bold text-white bg-lbc-orange hover:bg-[#d64d0e] rounded-lg shadow-md transition select-none transform hover:scale-105">
                Tout accepter
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        function initialiserBandeauCookies() {
            const bandeau = document.getElementById('cookie-banner');
            const boutonAccepter = document.getElementById('cookie-accept');
            const boutonRefuser = document.getElementById('cookie-refuse');
            
            // Clé de stockage unique (vérifiez qu'elle ne change pas)
            const CLE_STOCKAGE = 'leboncoin_consentement_cookie';

            if (!bandeau || !boutonAccepter || !boutonRefuser) return;

            // Fonction AJAX pour envoyer la stat
            function envoyerStatistique(choix) {
                const url = "{{ route('cookies.record') }}";
                const token = "{{ csrf_token() }}";

                // On ne bloque pas l'exécution si fetch échoue
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ choix: choix })
                }).catch(erreur => console.warn('Statistique cookie non envoyée:', erreur));
            }

            function fermerBandeau() {
                bandeau.classList.add('translate-y-full');
                setTimeout(() => {
                    bandeau.classList.add('hidden');
                }, 300);
            }

            // --- VÉRIFICATION DU CONSENTEMENT ---
            
            // On essaie de lire le localStorage
            let consentementActuel = null;
            try {
                consentementActuel = localStorage.getItem(CLE_STOCKAGE);
                console.log('État cookie actuel :', consentementActuel); // DEBUG
            } catch(e) {
                console.warn('LocalStorage non accessible (Navigation privée ?)');
            }

            // Si aucun choix n'a été fait ("null"), on affiche le bandeau
            if (!consentementActuel) {
                console.log('Aucun choix trouvé, affichage du bandeau.'); // DEBUG
                bandeau.classList.remove('hidden');
                setTimeout(() => {
                    bandeau.classList.remove('translate-y-full');
                }, 100);
            } else {
                console.log('Choix déjà fait, bandeau masqué.'); // DEBUG
                bandeau.classList.add('hidden');
            }

            // --- GESTION DES CLICS ---

            boutonAccepter.onclick = function(e) {
                e.preventDefault();
                console.log("Clic: Accepter");
                try {
                    localStorage.setItem(CLE_STOCKAGE, 'accepted');
                    console.log("Choix 'accepted' sauvegardé dans localStorage");
                } catch(e) { console.error("Erreur écriture localStorage", e); }
                
                envoyerStatistique('accepted');
                fermerBandeau();
            };

            boutonRefuser.onclick = function(e) {
                e.preventDefault();
                console.log("Clic: Refuser");
                try {
                    localStorage.setItem(CLE_STOCKAGE, 'refused');
                    console.log("Choix 'refused' sauvegardé dans localStorage");
                } catch(e) { console.error("Erreur écriture localStorage", e); }

                envoyerStatistique('refused');
                fermerBandeau();
            };
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialiserBandeauCookies);
        } else {
            initialiserBandeauCookies();
        }
    })();
</script>