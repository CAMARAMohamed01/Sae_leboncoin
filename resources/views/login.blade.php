<form action="{{ route('login.submit') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="idUtilisateur">Votre ID Utilisateur</label>
        <input type="text" name="idUtilisateur" id="idUtilisateur" required autofocus>
    </div>

    <div class="form-group">
        <label for="motdepasse">Votre Mot de Passe</label>
        <input type="password" name="motdepasse" id="motdepasse" required>
    </div>


    <button type="submit">Se connecter</button>


    <div>
        <p>Vous n'avez pas encore de compte ?</p>
        <a href='inscription'>S'inscrire</a>
    </div>
</form>