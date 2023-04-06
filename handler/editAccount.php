<?php
// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";

require_once ROOT . '/includes/classes/db.class.php';
require ROOT . '/includes/conf.php';

$db = new DB();
$conn = $db->cnx();

// on sélectionne l'utilisateur dans la base de données pour comparer les données
$pseudo = htmlspecialchars($_SESSION['auth']['pseudo']);
$email = htmlspecialchars($_SESSION['auth']['email']);
$sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo OR email = :email";
$selection = $conn->prepare($sql);
$selection->bindParam(':pseudo', $pseudo);
$selection->bindParam(':email', $email);
$selection->execute();

$user_result = $selection->fetch(PDO::FETCH_ASSOC);
// debug($user_result);

// debug($_POST);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Si on ne provient pas de la page de modification de compte, on redirige vers la page d'accueil
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== NULL) {
    if ($_SERVER['HTTP_REFERER'] !== ($_SERVER['HTTP_ORIGIN'] . "/profile/" . $_SESSION['auth']['pseudo'] . "/settings")) {
        header('Location: /');
        exit();
    }
} else {
    header('Location: /');
    exit();
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Si on a reçu des données du formulaire
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);
    $username = htmlspecialchars($_POST['username']);
    $photo_profil = isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != '' ? uploadImageAndRetrieveUrl($_FILES["profile_image"]) : htmlspecialchars($user_result['photo_profil']);

    // si un champ est différent de la base de données, on le modifie, sinon on ne fait rien
    $fields = [
        'pseudo' => $username,
        'photo_profil' => $photo_profil,
    ];

    $diff = array_diff_assoc($fields, $user_result);

    if ($diff == []) {
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
        exit();
    }

    // on regarde si le pseudo est déjà utilisé si il est différent de celui de la base de données
    if ($username != $_SESSION['auth']['pseudo'] && (isset($diff['pseudo']) && $diff['pseudo'] != '')) {
        $sql = "SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo";
        $selection = $conn->prepare($sql);
        $selection->bindParam(':pseudo', $username);
        $selection->execute();

        $pseudo_result = $selection->fetch(PDO::FETCH_ASSOC);

        if ($pseudo_result) {
            $_SESSION['error'] = 'error';
            header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
            exit();
        }
    }

    // on modifie les données dans la base de données
    $sql = "UPDATE utilisateur SET ";
    foreach ($diff as $key => $value) {
        $sql .= $key . " = :" . $key . ", ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= " WHERE id_user = :id_user";

    $modification = $conn->prepare($sql);
    $modification->bindParam(':id_user', $id_user);
    foreach ($diff as $key => $value) {
        $modification->bindParam(':' . $key, $value);
    }
    $modification->execute();

    if ($modification->rowCount() > 0) {
        // si le pseudo n'est pas déjà utilisé et qu'il est différent de celui de la base de données, on modifie le pseudo en session
        if (!$pseudo_result && $username != $_SESSION['auth']['pseudo'] && (isset($diff['pseudo']) && $diff['pseudo'] != '')) {
            $_SESSION['auth']['pseudo'] = $username;
        }
        $_SESSION['error'] = 'success';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    } else {
        $_SESSION['error'] = 'error';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    }
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Si on modifie le mot de passe
if (isset($_POST['oldpassword']) && isset($_POST['newpassword'])) {
    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);
    $oldpassword = htmlspecialchars($_POST['oldpassword']);
    $newpassword = htmlspecialchars($_POST['newpassword']);
    $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);

    // on vérifie que l'ancien mot de passe est correct
    if (password_verify($oldpassword, $user_result['mdp'])) {
        // on modifie le mot de passe
        $sql = "UPDATE utilisateur SET mdp = :mdp WHERE id_user = :id_user";
        $modification = $conn->prepare($sql);
        $modification->bindParam(':id_user', $id_user);
        $modification->bindParam(':mdp', $hashed_password);
        $modification->execute();

        if ($modification->rowCount() > 0) {
            $_SESSION['auth']['mdp'] = $hashed_password;
            $_SESSION['error'] = 'success';
            header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
        } else {
            $_SESSION['error'] = 'error';
            header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
        }
    } else {
        $_SESSION['error'] = 'error';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    }
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Si on modifie l'email
if (isset($_POST['newemail'])) {
    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);
    $newemail = htmlspecialchars($_POST['newemail']);

    // on modifie l'email
    $sql = "UPDATE utilisateur SET email = :email WHERE id_user = :id_user";
    $modification = $conn->prepare($sql);
    $modification->bindParam(':id_user', $id_user);
    $modification->bindParam(':email', $newemail);
    $modification->execute();

    if ($modification->rowCount() > 0) {
        $_SESSION['auth']['email'] = $newemail;
        $_SESSION['error'] = 'success';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    } else {
        $_SESSION['error'] = 'error';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    }
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Si on supprime le compte
if (isset($_POST['deleteAccount'])) {
    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);

    $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
    $suppression = $conn->prepare($sql);
    $suppression->bindParam(':id_user', $id_user);
    $suppression->execute();

    if ($suppression->rowCount() > 0) {
        header('Location: /logout');
    } else {
        $_SESSION['error'] = 'error';
        header('Location: /profile/' . $_SESSION['auth']['pseudo'] . '/settings');
    }
}
