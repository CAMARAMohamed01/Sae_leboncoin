document.addEventListener('DOMContentLoaded', () => {
    // SÉLECTIONNER TOUS les inputs ayant la classe 'js-autocomplete-input'
    const autocompleteInputs = document.querySelectorAll('.js-autocomplete-input');
    
    // ** URL de l'API gouvernementale pour les communes françaises **
    const apiUrl = "https://geo.api.gouv.fr/communes";

    autocompleteInputs.forEach(input => {
        // Pour chaque input trouvé, trouver le conteneur de résultats associé 
        // (il est généralement le voisin ou dans le même parent)
        const resultsContainer = input.closest('.relative').querySelector('.js-autocomplete-results');
        
        // S'assurer que le conteneur existe avant de continuer
        if (!resultsContainer) {
            console.warn('Conteneur de résultats non trouvé pour l\'input:', input);
            return;
        }

        let debounceTimeout;

        // Fonction pour masquer les résultats
        // Cette fonction utilise le 'resultsContainer' propre à cet input.
        const hideResults = () => {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
        };

        // 2. Appel AJAX au Géo-API de l'État via l'API Fetch
        // Cette fonction reste la même car elle est indépendante du DOM
        const fetchAutocompleteResults = async (query) => {
            try {
                const url = `${apiUrl}?nom=${encodeURIComponent(query)}&fields=nom,codesPostaux&limit=10&boost=population`;
                const response = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });

                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la récupération des communes. Code: ' + response.status);
                }

                const communes = await response.json();
                displayResults(communes, input, resultsContainer); // On passe l'input et le conteneur à la fonction d'affichage

            } catch (error) {
                console.error('Erreur Autocomplétion Géo-API:', error);
                hideResults();
            }
        };

        // 3. Affichage des résultats dans le conteneur
        // Cette fonction doit être modifiée pour accepter l'input et le conteneur spécifiques.
        const displayResults = (communes, currentInput, currentResultsContainer) => {
            currentResultsContainer.innerHTML = '';
            
            if (communes.length === 0) {
                const noResult = document.createElement('div');
                noResult.className = 'px-4 py-2 text-left text-gray-500 italic';
                noResult.textContent = "Aucune ville trouvée.";
                currentResultsContainer.appendChild(noResult);
                currentResultsContainer.classList.remove('hidden');
                return;
            }
            
            currentResultsContainer.classList.remove('hidden');

            communes.forEach(commune => {
                const nomVille = commune.nom;
                const codePostal = commune.codesPostaux ? commune.codesPostaux[0] : '';
                const displayText = codePostal ? `${nomVille} (${codePostal})` : nomVille;

                const item = document.createElement('div');
                item.className = 'px-4 py-2 cursor-pointer text-left text-gray-700 hover:bg-lbc-orange/10 transition truncate';
                item.textContent = displayText;
                
                // Gérer le clic sur une suggestion (applique à l'input courant)
                item.addEventListener('click', () => {
                    currentInput.value = nomVille; 
                    hideResults();
                });
                
                currentResultsContainer.appendChild(item);
            });
        };


        // 1. Écoute des saisies dans le champ avec un délai (debounce)
        input.addEventListener('input', () => {
            clearTimeout(debounceTimeout);
            const query = input.value.trim();

            if (query.length < 2) {
                hideResults();
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetchAutocompleteResults(query);
            }, 300); 
        });
        
        // 5. Gérer le focus pour ré-afficher la liste si elle contenait des résultats avant
        input.addEventListener('focus', () => {
            if (resultsContainer.children.length > 0 && input.value.trim().length >= 2) {
                resultsContainer.classList.remove('hidden');
            }
        });
        
        // 4. Masquer les résultats lorsque l'utilisateur clique en dehors de l'input ou des résultats
        // ATTENTION : Cette logique doit être appliquée à TOUS les clics en dehors de TOUS les éléments concernés
        // Pour simplifier et éviter les conflits, nous allons déplacer cette logique en dehors de la boucle forEach

    }); // Fin de la boucle forEach
    
    
    // 4. LOGIQUE GLOBALE : Masquer les résultats lorsque l'utilisateur clique en dehors
    // Cette partie est exécutée une seule fois.
    document.addEventListener('click', (event) => {
        // Vérifie si le clic est sur un input d'autocomplétion ou un conteneur de résultats
        const isClickInside = event.target.closest('.js-autocomplete-input') || 
                              event.target.closest('.js-autocomplete-results');
        
        if (!isClickInside) {
            // Si le clic est en dehors, masquer tous les conteneurs de résultats
            document.querySelectorAll('.js-autocomplete-results').forEach(container => {
                if (!container.classList.contains('hidden')) {
                    container.classList.add('hidden');
                    container.innerHTML = '';
                }
            });
        }
    });

});