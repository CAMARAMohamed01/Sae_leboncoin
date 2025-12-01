<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Entreprise</title>
</head>
<body>
    
    <form action="{{ route('entreprise.verifier_siret') }}" method="POST" id="siret-form">
        @csrf

        <label for="siret">SIRET :</label>
        <input type="text" name="siret" id="siret" minlength="14" maxlength="14" pattern="\d*" inputmode="numeric" required>
        
        @error('siret')
            <div style="color: red">{{ $message }}</div>
        @enderror

        <button type="submit" id="verifier-btn">Vérifier</button>
    </form>

    
    <div id="entreprise-info" style="display: none; border: 1px solid #ccc; padding: 15px; margin-top: 20px;">
        <h3>✅ Informations trouvées :</h3>
        
        <p><strong>Nom:</strong> <span id="info-nom"></span></p>
        <p><strong>Adresse:</strong> <span id="info-adresse"></span></p>
        <p><strong>Code Postal / Ville:</strong> <span id="info-code-postal"></span> <span id="info-ville"></span></p>
        
        <form action="{{ route('inscription.entreprise.info') }}" method="POST" id="confirmation-form" style="margin-top: 15px;">
            @csrf
            
            <input type="hidden" name="siret" id="hidden-siret">
            <input type="hidden" name="nom" id="hidden-nom">
            <input type="hidden" name="adresse" id="hidden-adresse">
            <input type="hidden" name="ville" id="hidden-ville">
            <input type="hidden" name="code_postal" id="hidden-code-postal">
            
            <button type="submit">Confirmer l'inscription</button>
        </form>
    </div>

    <script>
        document.getElementById('siret-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
    
            const form = e.target;
            const siretInput = document.getElementById('siret');
            const url = form.action;
            const csrfToken = form.querySelector('input[name="_token"]').value;
            const infoDiv = document.getElementById('entreprise-info');
            const verifierBtn = document.getElementById('verifier-btn');
            
            // Masquer les infos précédentes et gérer l'état du bouton
            infoDiv.style.display = 'none';
            verifierBtn.disabled = true;
            verifierBtn.textContent = 'Vérification en cours...';
    
            const data = new FormData();
            data.append('siret', siretInput.value);
            data.append('_token', csrfToken); 
    
            fetch(url, {
                method: 'POST',
                body: data
            })
            .then(response => {
                // Si le statut est 404/400/500, cela sera traité ici
                if (!response.ok) {
                    return response.json().then(err => { 
                        // Lève une erreur JavaScript avec le message du serveur (JSON)
                        throw new Error(err.message || `Erreur serveur (${response.status})`); 
                    }).catch(error => {
                        // Si le serveur n'a pas retourné de JSON (i.e. HTML page d'erreur), on gère l'erreur générale
                        throw new Error("Erreur de vérification: Problème d'accès au service ou erreur PHP.");
                    });
                }
                return response.json();
            })
            .then(data => {
                const messageZone = document.getElementById('message-zone') || { innerHTML: '' };
                messageZone.innerHTML = ''; // Nettoyer les messages précédents

                if (data.success) {
                    const entreprise = data.entreprise;
                    
                    // 1. Affiche les informations récupérées
                    document.getElementById('info-nom').textContent = entreprise.nom;
                    document.getElementById('info-adresse').textContent = entreprise.adresse;
                    document.getElementById('info-ville').textContent = entreprise.ville;
                    document.getElementById('info-code-postal').textContent = entreprise.code_postal;
                    
                    // 2. Remplit les champs cachés pour le formulaire de confirmation
                    document.getElementById('hidden-siret').value = entreprise.siret;
                    document.getElementById('hidden-nom').value = entreprise.nom;
                    document.getElementById('hidden-adresse').value = entreprise.adresse;
                    document.getElementById('hidden-ville').value = entreprise.ville;
                    document.getElementById('hidden-code-postal').value = entreprise.code_postal;
    
                    infoDiv.style.display = 'block'; 
                } else {
                    // Si le serveur a répondu 200 OK mais avec success: false (ce qui ne devrait pas arriver ici)
                    alert(data.message || 'Vérification échouée.');
                }
            })
            .catch(error => {
                // Gestion de l'erreur côté front-end
                console.error('Erreur de vérification (Ajax):', error);
                alert(error.message); // Affiche le message d'erreur du serveur
            })
            .finally(() => {
                verifierBtn.disabled = false;
                verifierBtn.textContent = 'Vérifier';
            });
        });
    </script>
</body>
</html>