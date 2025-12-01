<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<hr style="margin: 40px 0;">

<section>
    <h2>Continuons avec plus d'informations</h2>
    <p>Vérifiez que toutes les informations sont correctes et sélectionnez votre secteur d'activité.</p>

    <form action="/" method="GET">
        <label for="siret_display">SIRET*</label><br>
        <input type="text" id="siret_display" name="siret_display" value="" disabled>
        <br><br>

        <label for="societe">Société*</label><br>
        <input type="text" id="societe" name="societe" value="" required><br>
        <small>Le nom de votre société sera visible sur vos annonces</small>
        <br><br>

        <label for="adresse">Adresse*</label><br>
        <input type="text" id="adresse" name="adresse" value="" required>
        <br><br>

        <label for="ville">Ville*</label><br>
        <input type="text" id="ville" name="ville" value="" required>
        <br><br>

        <label for="cp">Code postal*</label><br>
        <input type="text" id="cp" name="cp" value="" required>
        <br><br>

        <label for="secteur">Secteur d'activité*</label><br>
        <select id="secteur" name="secteur">
            <option value="vacances" selected>Vacances</option>
            <option value="autre">Autre</option>
        </select>
        <br><br>

        <button type="submit">Continuer</button>
    </form>
</section>
</body>
</html>