document.addEventListener('DOMContentLoaded', () => {
    const autocompleteInputs = document.querySelectorAll('.js-autocomplete-input');
    
    const apiUrl = "https://geo.api.gouv.fr/communes";

    autocompleteInputs.forEach(input => {
        const resultsContainer = input.closest('.relative').querySelector('.js-autocomplete-results');
        
        if (!resultsContainer) {
            console.warn('Conteneur de résultats non trouvé pour l\'input:', input);
            return;
        }

        let debounceTimeout;

        const hideResults = () => {
            resultsContainer.classList.add('hidden');
            resultsContainer.innerHTML = '';
        };

        const fetchAutocompleteResults = async (query) => {
            try {
                const url = `${apiUrl}?nom=${encodeURIComponent(query)}&fields=nom,codesPostaux&limit=10&boost=population`;
                const response = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });

                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la récupération des communes. Code: ' + response.status);
                }

                const communes = await response.json();
                displayResults(communes, input, resultsContainer);

            } catch (error) {
                console.error('Erreur Autocomplétion Géo-API:', error);
                hideResults();
            }
        };

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
                
                item.addEventListener('click', () => {
                    currentInput.value = nomVille; 
                    hideResults();
                });
                
                currentResultsContainer.appendChild(item);
            });
        };


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

        input.addEventListener('focus', () => {
            if (resultsContainer.children.length > 0 && input.value.trim().length >= 2) {
                resultsContainer.classList.remove('hidden');
            }
        });
        
    }); 
    
    
    document.addEventListener('click', (event) => {
        const isClickInside = event.target.closest('.js-autocomplete-input') || 
                              event.target.closest('.js-autocomplete-results');
        
        if (!isClickInside) {
            document.querySelectorAll('.js-autocomplete-results').forEach(container => {
                if (!container.classList.contains('hidden')) {
                    container.classList.add('hidden');
                    container.innerHTML = '';
                }
            });
        }
    });

});