<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/styles/app.css">
    <title>Connexion PHP</title>
</head>

<body>
    <section></section>
    <div class="form_cnx">
        <form action="cnx.php" method="post">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" name="login" id="login">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <input type="submit" value="Se connecter">
            </div>
        </form>
    </div>
</body>

</html>