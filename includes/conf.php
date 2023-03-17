<?php
// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";

// C'EST PAS LÀ QU'IL Y A LA CREATION DU PDO DE LA BDD CONFONDEZ PAS . TODO : Enlever ce message
require_once ROOT . '/includes/classes/db.class.php';


$db = new DB();
$conn = $db->cnx();

if (isset($_SESSION['auth']['verif'])) {

    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);
    $sql = "SELECT auth FROM utilisateur WHERE id_user = :id_user";
    $selection = $conn->prepare($sql);
    $selection->bindParam(':id_user', $id_user);
    $selection->execute();

    $result = $selection->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (gettype($result['auth']) === 'NULL') {
            $_SESSION['auth']['bdd_auth'] = 'non authentifié';
        } else {
            $_SESSION['auth']['verif'] = 'validé';
            $_SESSION['auth']['bdd_auth'] = 'authentifié';
        }
    }
}


if (isset($_COOKIE['auth']) && !isset($_SESSION['auth'])) {

    // un cookie a été trouvé on est automatiquement authentifié
    $auth = $_COOKIE['auth'];
    $auth = explode('--', $auth);

    $id_user = htmlspecialchars($auth[0]);

    $sql = "SELECT id_user, pseudo, mdp FROM utilisateur WHERE id_user = :id_user";
    $selection = $conn->prepare($sql);
    $selection->bindParam(':id_user', $id_user);
    $selection->execute();

    $result = $selection->fetch(PDO::FETCH_ASSOC);

    $key = sha1($result['pseudo'] . $result['mdp'] . $_SERVER['REMOTE_ADDR']);
    if ($key === $auth[1]) {
        $_SESSION['auth'] = array(
            'id_user' => $result['id_user'],
            'pseudo' => $result['pseudo'],
            'mdp' => $result['mdp']
        );
        createCookie($result['id_user'], $result['pseudo'], $result['mdp']);
    } else {
        unsetCookie();
    }
}
