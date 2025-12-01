<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire Entreprise</title>
</head>
<body>

<section>
    <h2>Commençons par votre entreprise</h2>
    <p>Saisissez votre numéro de SIRET pour remplir automatiquement les coordonnées...</p>

    <form action="/entreprise/siret" method="GET">
        
        <label for="siret_search">SIRET*</label><br>
        <input type="text" id="siret_search" name="siret" value="" required>
        <br>
        <br>

        <button type="submit">Continuer</button>
        
        <br>
        <small>*Champs requis</small>
    </form>
</section>
</body>
</html>