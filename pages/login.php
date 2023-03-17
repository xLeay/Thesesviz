<?php

// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";

if (isset($_GET['method'])) {
    if ($_GET['method'] == 'signin') {
        $method = 'signin';
    } else if ($_GET['method'] == 'signup') {
        $method = 'signup';
    } else if ($_GET['method'] == 'verify') {
        $method = 'verify';
    }
} else {
    $method = 'none';
}

// debug($_SESSION);
// debug($_COOKIE);

?>

<div class="main_container">
    <section>
        <?php if ($method == 'none') : ?>

            <div class="connection_container">
                <div class="signin__item">
                    <button class="btn cnx-btn">Connexion</button>
                </div>

                <div class="signup__item">
                    <button class="btn ins-btn">Inscription</button>
                </div>

            </div>
        <?php elseif ($method == 'signin') : ?>
            <?php include ROOT . "/includes/components/auth/sign_in.php"; ?>
        <?php elseif ($method == 'signup') : ?>
            <?php include ROOT . "/includes/components/auth/sign_up.php"; ?>
        <?php elseif ($method == 'verify') : ?>
            <?php include ROOT . "/includes/components/auth/verifyCode.php"; ?>
        <?php endif; ?>
    </section>
</div>

<script>
    <?php if ($method == 'none') : ?>
        const signinBtn = document.querySelector(".cnx-btn");
        const signupBtn = document.querySelector(".ins-btn");

        signinBtn.addEventListener("click", () => {
            window.location.href = "/login?method=signin";
        });

        signupBtn.addEventListener("click", () => {
            window.location.href = "/login?method=signup";
        });
    <?php endif; ?>
</script>