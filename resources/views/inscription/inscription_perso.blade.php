

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Email</title>
</head>
<body>

    <h2>Commençons par un e-mail</h2>

    <form action="/perso/paramètre" method="GET"> 
        
        <label for="email">E-mail *</label><br>
        <input type="email" id="email" name="email" value="pascal.colin@univ-smb.fr" required>
        <br><br>

        <div>
            <input type="checkbox" id="newsletter" name="newsletter">
            <label for="newsletter">Recevoir les bons plans de nos sites partenaires</label>
        </div>
        <br>

        <button type="submit">Suivant</button>

    </form>

</body>
</html>