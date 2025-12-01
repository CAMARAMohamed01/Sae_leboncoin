<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>

    <h1>Créer un compte</h1>

    {{-- Affichage des erreurs globales si besoin --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('inscription.traitement') }}">
        {{-- Token de sécurité OBLIGATOIRE --}}
        @csrf

        {{-- Champ Nom --}}
        <label for="name">Nom :</label><br>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        <br>
        @error('name')
            <small style="color: red;">{{ $message }}</small>
        @enderror
        <br><br>

        {{-- Champ Email --}}
        <label for="email">Email :</label><br>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        <br>
        @error('email')
            <small style="color: red;">{{ $message }}</small>
        @enderror
        <br><br>

        {{-- Champ Mot de passe --}}
        <label for="password">Mot de passe :</label><br>
        <input type="password" name="password" id="password" required>
        <br>
        @error('password')
            <small style="color: red;">{{ $message }}</small>
        @enderror
        <br><br>

        {{-- Champ Confirmation Mot de passe --}}
        <label for="password_confirmation">Confirmer le mot de passe :</label><br>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
        <br><br>

        {{-- Bouton --}}
        <button type="submit">S'inscrire</button>

    </form>

</body>
</html>