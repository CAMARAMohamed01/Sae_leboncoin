{{-- 1. BANDEAU PRINCIPAL (Barre du bas) --}}
<div id="cookie-banner" 
     class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_20px_rgba(0,0,0,0.15)] border-t border-gray-200 z-[9999] hidden p-4 md:p-6 transition-transform duration-500 transform translate-y-full"
     style="z-index: 9998;">
    
    <div class="container mx-auto max-w-6xl flex flex-col md:flex-row items-center justify-between gap-6">
        
        {{-- Texte explicatif --}}
        <div class="flex-1 text-sm text-gray-600">
            <p class="font-bold text-gray-900 mb-2 flex items-center gap-2 text-lg">
                <i class="fa-solid fa-cookie-bite text-lbc-orange"></i> Gérer vos préférences
            </p>
            <p class="leading-relaxed">
                En cliquant sur « Tout accepter », vous acceptez l'utilisation de cookies pour améliorer la navigation, mesurer l'audience et vous proposer des publicités adaptées. 
                Vous pouvez changer d'avis à tout moment via le lien "Cookies" en bas de page.
                <a href="{{ route('legal.privacy') }}" class="underline text-lbc-blue hover:text-blue-700 font-bold">Lire la politique de confidentialité</a>.
            </p>
        </div>

        {{-- Boutons d'action --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full md:w-auto">
            <button id="cookie-customize" type="button" class="w-full sm:w-auto cursor-pointer px-4 py-2.5 text-sm font-bold text-lbc-blue bg-blue-50 hover:bg-blue-100 rounded-lg transition border border-blue-200">
                Paramétrer
            </button>
            <button id="cookie-refuse" type="button" class="w-full sm:w-auto cursor-pointer px-4 py-2.5 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition border border-gray-300">
                Continuer sans accepter
            </button>
            <button id="cookie-accept" type="button" class="w-full sm:w-auto cursor-pointer px-6 py-2.5 text-sm font-bold text-white bg-[#ec5a13] hover:bg-[#d64d0e] rounded-lg shadow-md transition transform hover:scale-105">
                Tout accepter
            </button>
        </div>
    </div>
</div>

{{-- 2. MODALE DE PERSONNALISATION (Cachée par défaut) --}}
<div id="cookie-modal" class="fixed inset-0 bg-black/60 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-sm" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto flex flex-col">
        
        {{-- Header Modale --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
            <h3 class="text-xl font-extrabold text-gray-900">Préférences de cookies</h3>
            <button id="cookie-modal-close" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        {{-- Corps Modale (Options) --}}
        <div class="p-6 space-y-6">
            <p class="text-sm text-gray-600">
                Vous pouvez choisir d'activer ou de désactiver certaines catégories de cookies. 
                Les cookies "Nécessaires" sont indispensables au fonctionnement du site.
            </p>

            {{-- Option 1 : Nécessaires --}}
            <div class="flex items-start justify-between gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50">
                <div>
                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                        Fonctionnement (Strictement nécessaires)
                        <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded uppercase">Obligatoire</span>
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">Nécessaires à la navigation, la sécurité et la connexion (Session, Panier).</p>
                </div>
                <div class="relative inline-flex items-center cursor-not-allowed opacity-60">
                    <input type="checkbox" checked disabled class="sr-only peer">
                    <div class="w-11 h-6 bg-lbc-orange peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </div>
            </div>

            {{-- Option 2 : Analytiques --}}
            <div class="flex items-start justify-between gap-4 p-4 rounded-xl border border-gray-200 hover:border-blue-200 transition">
                <div>
                    <h4 class="font-bold text-gray-900">Mesure d'audience (Analytiques)</h4>
                    <p class="text-xs text-gray-500 mt-1">Nous aident à comprendre comment vous utilisez le site pour l'améliorer (Pages vues, temps passé...).</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="consent-analytics" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-lbc-blue"></div>
                </label>
            </div>

            {{-- Option 3 : Marketing --}}
            <div class="flex items-start justify-between gap-4 p-4 rounded-xl border border-gray-200 hover:border-blue-200 transition">
                <div>
                    <h4 class="font-bold text-gray-900">Publicité personnalisée</h4>
                    <p class="text-xs text-gray-500 mt-1">Permettent de vous présenter des annonces pertinentes en fonction de vos centres d'intérêt.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="consent-marketing" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-lbc-blue"></div>
                </label>
            </div>
        </div>

        {{-- Footer Modale --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
            <button id="cookie-save-custom" class="w-full sm:w-auto px-6 py-3 bg-[#ec5a13] hover:bg-[#d64d0e] text-white font-bold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                Enregistrer mes choix
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        function initialiserBandeauCookies() {

            const bandeau = document.getElementById('cookie-banner');
            const modal = document.getElementById('cookie-modal');

            const btnToutAccepter = document.getElementById('cookie-accept');
            const btnToutRefuser = document.getElementById('cookie-refuse');
            const btnParametrer = document.getElementById('cookie-customize');
            const btnFermerModal = document.getElementById('cookie-modal-close');
            const btnEnregistrer = document.getElementById('cookie-save-custom');
            
            const checkAnalytics = document.getElementById('consent-analytics');
            const checkMarketing = document.getElementById('consent-marketing');
            
            const CLE_STOCKAGE = 'leboncoin_consentement';

            if (!bandeau) return;

            
            function envoyerStatistique(choix) {
                const url = "{{ route('cookies.record') }}";
                const token = "{{ csrf_token() }}";

                let statutBackend = choix;
                if (choix === 'custom') {
                    statutBackend = (checkAnalytics.checked || checkMarketing.checked) ? 'accepted' : 'refused';
                }

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({ choix: statutBackend })
                }).catch(e => console.warn('Stat cookie error', e));
            }

            function afficherBandeau() {
                bandeau.classList.remove('hidden');
                setTimeout(() => bandeau.classList.remove('translate-y-full'), 100);
            }

            function cacherBandeau() {
                bandeau.classList.add('translate-y-full');
                setTimeout(() => bandeau.classList.add('hidden'), 500);
                modal.classList.add('hidden');
            }

            
            const consentement = JSON.parse(localStorage.getItem(CLE_STOCKAGE));
            if (!consentement) {
                afficherBandeau();
            } else {
                if(checkAnalytics) checkAnalytics.checked = consentement.analytics;
                if(checkMarketing) checkMarketing.checked = consentement.marketing;
            }

            btnToutAccepter.onclick = () => {
                const choix = { necessary: true, analytics: true, marketing: true, timestamp: new Date() };
                localStorage.setItem(CLE_STOCKAGE, JSON.stringify(choix));
                envoyerStatistique('accepted');
                cacherBandeau();
            };

            btnToutRefuser.onclick = () => {
                const choix = { necessary: true, analytics: false, marketing: false, timestamp: new Date() };
                localStorage.setItem(CLE_STOCKAGE, JSON.stringify(choix));
                envoyerStatistique('refused');
                cacherBandeau();
            };

            btnParametrer.onclick = () => {
                modal.classList.remove('hidden');
            };

            btnFermerModal.onclick = () => {
                modal.classList.add('hidden');
            };

            btnEnregistrer.onclick = () => {
                const choix = {
                    necessary: true,
                    analytics: checkAnalytics.checked,
                    marketing: checkMarketing.checked,
                    timestamp: new Date()
                };
                localStorage.setItem(CLE_STOCKAGE, JSON.stringify(choix));
                
                envoyerStatistique('custom');
                
                cacherBandeau();
            };
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialiserBandeauCookies);
        } else {
            initialiserBandeauCookies();
        }
    })();
</script>