<a class="back_a" href="/pages">
    <div class="back">
        <span class="material-symbols-outlined arrow_back">arrow_back</span>
        <p>Retour</p>
    </div>
</a>

<form action="../pages/auth/cnx.php?from=signup" method="post" class="connect_form">
    <label for="username">Login</label>
    <input class="input logi" type="text" name="username" placeholder="Nom d'utilisateur" required>
    <label for="password">Mot de passe</label>
    <input class="input passw" type="password" name="password" placeholder="Mot de passe" required>
    <button class="btn auth_btn" type="submit" value="Inscription">Inscription</button>
</form>