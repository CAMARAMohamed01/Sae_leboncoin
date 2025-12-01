<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres</title>
</head>
<body>

    <h1>Paramètres</h1>

    <form>
        <h2>Informations de compte</h2>

        <div>
            <input type="radio" id="monsieur" name="civilite" value="monsieur">
            <label for="monsieur">Monsieur</label>
            
            <input type="radio" id="madame" name="civilite" value="madame">
            <label for="madame">Madame</label>
        </div>
        <br>

        <div>
            <label for="nom">Nom :</label><br>
            <input type="text" id="nom" name="nom" value="">
        </div>
        <br>

        <div>
            <label for="prenom">Prénom :</label><br>
            <input type="text" id="prenom" name="prenom" value="">
        </div>
        <br>

        <div>
            <label for="date">Date de naissance :</label><br>
            <input type="text" id="date" name="date_naissance" value="">
        </div>
        <br>

    </form>

    <hr>

    <form action="/" method="GET">

    <div>
        <label for="adresse">Adresse (champ requis) :</label><br>
        <input type="text" id="adresse" name="adresse" value="" required>
    </div>
    <br>

    <div>
        <label for="ville">Ville ou code postal (champ requis) :</label><br>
        <input type="text" id="ville" name="ville" value="" required>
    </div>
    <br>

    <button type="submit">Enregistrer les modifications</button>

</form>
</body>
</html>