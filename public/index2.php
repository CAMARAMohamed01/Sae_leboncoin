<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Leboncoin</title>
</head>
<body> <header>
    <nav id="Menu">
        <img src="{{ asset('images/leboncoinLogo.svg') }}" alt="Logo"/>
        
        <button id="Btn_Dépot_Annonce">Déposer une annonce</button>
        <button id="Btn_Recherches_Enregistrés" class="Btn_Menu">Mes recherches</button>
        <button id="Btn_Favoris" class="Btn_Menu">Favoris</button>
        <button id="Btn_Messages" class="Btn_Menu">Messages</button>

        @auth
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="Btn_Menu" style="background-color: #fca5a5;">Se déconnecter</button>
            </form>
        @else
            <a href="{{ route('login') }}" id="Btn_Se_Connecter" class="Btn_Menu" style="text-decoration: none; text-align:center; display:inline-block;">
                Se connecter
            </a>
        @endauth

    </nav>
</header>

    <form class="recherche" action="">
        <input placeholder="Choisir une localisation" type="text" value="">
        
        <select name="Type_d'hébergement" id="Type_d'hébergement">
            <option value="" disabled selected>Type_d'hébergement</option> 
            <option value="Appartement">Appartement</option>
            <option value="Maison">Maison</option>
            <option value="Cabanne">Cabane</option> </select>
    </form>
    <label for="">nombre d'annonces trouvées : 0</label>

<footer>
</footer>

</body>
</html>