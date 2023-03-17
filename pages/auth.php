<?php
// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";


require_once ROOT . '/includes/classes/db.class.php';
require ROOT . '/includes/conf.php';

$db = new DB();
$conn = $db->cnx();


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI AUCUN POST, REDIRECTION VERS LA PAGE D'ACCUEIL
if (empty($_POST)) {
    header('Location: /');
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// VÉRIFICATION DE LA DISPONIBILITÉ DU PSEUDO ET DE L'EMAIL
if (isset($_POST['ajax-check'])) {

    if (isset($_POST['nick'])) {
        $nick = htmlspecialchars($_POST['nick']);

        $sql = "SELECT pseudo FROM utilisateur WHERE pseudo = :pseudo";

        $selection = $conn->prepare($sql);
        $selection->bindParam(':pseudo', $nick);
        $selection->execute();

        $result = $selection->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['nick' => false]);
        } else {
            echo json_encode(['nick' => true]);
        }
    }

    if (isset($_POST['mail'])) {
        $mail = htmlspecialchars($_POST['mail']);

        $sql = "SELECT email FROM utilisateur WHERE email = :email";

        $selection = $conn->prepare($sql);
        $selection->bindParam(':email', $mail);
        $selection->execute();

        $result = $selection->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['mail' => false]);
        } else {
            echo json_encode(['mail' => true]);
        }
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// INSCRIPTION
if (isset($_POST['nick']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['signup'])) {
    $nick = htmlspecialchars($_POST['nick']);
    $mail = htmlspecialchars($_POST['mail']);
    $password = htmlspecialchars($_POST['password']);
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    $code = rand(100000, 999999);
    $mailSent = mail($mail, "Votre code de vérification", "Votre code de vérification est : $code");

    if ($mailSent) {
        $message = "code de vérification envoyé";
        $bienvenue = true;
    } else {
        $message = "erreur lors de l'envoi du code de vérification. Essayez avec l'adresse mail de l'université.";
    }

    $sql = "INSERT INTO utilisateur (pseudo, mdp, email) VALUES (:pseudo, :mdp, :email)";

    $insertion = $conn->prepare($sql);
    $insertion->bindParam(':pseudo', $nick);
    $insertion->bindParam(':mdp', $hashed_pass);
    $insertion->bindParam(':email', $mail);
    $insertion->execute();

    $id_user = $conn->lastInsertId();

    if ($insertion) {
        
        $_SESSION['auth'] = array(
            'id_user' => $id_user,
            'pseudo' => $nick,
            'email' => $mail,
            'mdp' => $hashed_pass,
            'verif' => 'en cours',
            'login_code' => $code,
            'login_timestamp' => time(),
            'message' => $message,
        );

        header('Location: /login?method=verify');
    } else {
        header('Location: /login?method=signup&error=1');
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// VALIDATION DU CODE DE VÉRIFICATION
if (isset($_POST['code']) && gettype($_POST['code']) == 'array') {

    $id_user = htmlspecialchars($_SESSION['auth']['id_user']);

    if ($_SESSION['auth']['login_timestamp'] + 60 * 5 > time()) {
        $code = !empty($_POST['code']) ? $_POST['code'] : array();
        foreach ($code as $key => $value) {
            $code[$key] = htmlspecialchars($value);
        }
        $code = implode($code);

        if ($code == $_SESSION['auth']['login_code']) {

            $message = "Code validé.";
            $_SESSION['auth']['message'] = $message;
            $sql = "UPDATE utilisateur SET auth = 1 WHERE id_user = :id_user";

            $update = $conn->prepare($sql);
            $update->bindParam(':id_user', $id_user);
            $update->execute();

            if ($update) {
                $_SESSION['auth']['verif'] = 'validé';
                unset($_SESSION['auth']['login_code']);
                header('Location: /');
            } else {
                $message = "Un problème inattendu est survenu.";
                $_SESSION['auth']['message'] = $message;
                header('Location: /login?method=verify&error=1');
            }
        } else {
            $message = "Code erroné.";
            $_SESSION['auth']['message'] = $message;
            header('Location: /login?method=verify&error=2');
        }
    } else {
        $message = "Le code de vérification a expiré. Veuillez en demander un nouveau.";
        $_SESSION['auth']['message'] = $message;
        header('Location: /login?method=verify&error=3');
    }
} else {
    $message = "Manque de données.";
    $_SESSION['auth']['message'] = $message;
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI LE CODE A ÉTÉ REDEMANDÉ
if (isset($_POST['resendcode']) && $_POST['resendcode'] == 'on') {

    $mail = htmlspecialchars($_SESSION['auth']['email']);

    $code = rand(100000, 999999);
    $mailSent = mail($mail, "Votre code de vérification", "Votre code de vérification est : $code");

    if ($mailSent) {
        $message = "code de vérification envoyé";
    } else {
        $message = "erreur lors de l'envoi du code de vérification. Essayez avec l'adresse mail de l'université.";
    }

    $_SESSION['auth'] = array(
        'id_user' => $_SESSION['auth']['id_user'],
        'pseudo' => $_SESSION['auth']['pseudo'],
        'email' => $mail,
        'mdp' => $_SESSION['auth']['mdp'],
        'verif' => 'en cours',
        'login_code' => $code,
        'login_timestamp' => time(),
        'message' => $message,
    );
    header('Location: /login?method=verify');
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CONNEXION
elseif (isset($_POST['nick_mail']) && isset($_POST['password']) && !isset($_POST['signup'])) {
    $nick_mail = htmlspecialchars($_POST['nick_mail']);
    $password = htmlspecialchars($_POST['password']);
    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // le pseudo ou l'email doivent correspondre
    $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo OR email = :email";

    $selection = $conn->prepare($sql);
    $selection->bindParam(':pseudo', $nick_mail);
    $selection->bindParam(':email', $nick_mail);
    $selection->execute();

    $result = $selection->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($password, $result['mdp'])) {
            if (isset($_POST['remember'])) {
                // setcookie('auth', $result['id_user'] . '--' . sha1($result['pseudo'] . $result['mdp'] . $_SERVER['REMOTE_ADDR']), time() + 3600 * 24 * 3, '/', 'thesesviz.test', false, true);
                createCookie($result['id_user'], $result['pseudo'], $result['mdp']);
                // TODO : mettre à true les 2 derniers paramètres quand le site est sur IONOS (seul le dernier l'est pour le moment)

            }

            $_SESSION['auth'] = array(
                'id_user' => $result['id_user'],
                'pseudo' => $result['pseudo'],
                'email' => $result['email'],
                'mdp' => $result['mdp']
            );

            header('Location: /');
        } else {
            header('Location: /login?error=1');
        }
    } else {
        header('Location: /login?error=1');
    }
}
