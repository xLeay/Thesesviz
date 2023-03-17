<?php


$db = new DB();
$conn = $db->cnx();

// Si l'utilisateur est connecté, mais n'a pas de mail, on le déconnecte
if (isset($_SESSION['auth']) && !isset($_SESSION['auth']['email'])) {
    echo '<script>window.location.href = "/logout";</script>';
}


if (isset($_SESSION['auth']) && isset($_SESSION['auth']['pseudo']) && $_SESSION['auth']['pseudo'] != NULL) {
    $nick = htmlspecialchars($_SESSION['auth']['pseudo']);

    // si l'utilisateur est connecté et qu'il a vérifié son email, on récupère son identicon
    $sql = "SELECT email, photo_profil FROM utilisateur WHERE pseudo = :pseudo AND auth = 1";
    $selection = $conn->prepare($sql);
    $selection->bindParam(':pseudo', $nick);
    $selection->execute();

    $result = $selection->fetch(PDO::FETCH_ASSOC);

    // debug($result);

    if ($result) {
        // debug('utilisateur trouvé');

        if ($result['photo_profil']) {
            $profil_pic = $result['photo_profil'];
            // debug('photo de profil trouvée');
        } else {
            $profil_pic = generateIdenticon($result['email']);
            // debug('photo de profil non trouvée');
        }
    } else {
        // debug('trouvé mais pas authentifié');
        $profil_pic = generateIdenticon($_SESSION['auth']['email']);
    }
    // debug($profil_pic);

} else {
    $nick = 'Connexion';
}



?>
<nav>
    <div class="nav_icon">
        <a id="backArrow">
            <span class="material-symbols-rounded back_icon">arrow_back</span>
        </a>
    </div>

    <form action="/q" method="get" class="search_thesis" autocomplete="off" enctype="text/plain" onsubmit="submitForm(event)">
        <div class="search_wrap">
            <div class="search__editor" spellcheck="false">
                <div class="search__editor-placeholder" style="padding-left: 15px;">
                    <div class="search__editor-placeholder-inner" style="outline: none;" contenteditable="true" placeholder="Recherche de thèse..."></div>
                </div>
            </div>

            <div class="search__icon" style="margin-right: 15px;">
                <span class="material-symbols-rounded search_icon">search</span>
            </div>
        </div>

        <!-- input factice qui gère le submit à la place de la div contenteditable qui permet de faire une recherche avancée -->
        <input type="text" id="search_input" style="display: none;" name="key">

        <!-- options de recherche -->
        <div class="search_options">
            <p style="font-weight: 700; font-size: 18px; text-transform: uppercase;">Options de recherche</p>

            <div class="sep" style="width: 100%; height: 1px; background: #C7BEB5;"></div>

            <div class="search_options__list">
                <div class="search_options__item">
                    <p><span class="important_info">titre:</span> titre (par défaut)</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">auteur:</span> nom</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">sujet:</span> libellé</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">depuis:</span> année</p>
                </div>
            </div>
        </div>
    </form>

    <div class="nav_icon">
        <?php if (isset($_SESSION['auth']) && isset($_SESSION['auth']['pseudo']) && $_SESSION['auth']['pseudo'] != NULL) : ?>
            <div class="profile">
                <img src="<?= $profil_pic ?>" alt="Identicon" style="border-radius: 5px; object-fit: cover; height: 32px; width: 32px;">

                <div class="profile_menu card">

                    <a href="/profile/<?= $nick ?>" class="profile_details__a">
                        <div class="profile_details__wrapper">
                            <p class="semibold"><?= $nick ?></p>
                            <p class="semibold secondary" style="font-size: 14px;"><?= $_SESSION['auth']['email'] ?></p>
                        </div>
                    </a>

                    <div class="nav_sep"></div>

                    <a href="/profile/<?= $nick ?>">
                        <span class="material-symbols-rounded nav_icon">person</span>
                        <p class="semibold">Profil</p>
                    </a>

                    <a href="/profile/<?= $nick ?>/settings" class="nav_settings_icon__a">
                        <span class="material-symbols-rounded nav_icon">settings</span>
                        <p class="semibold">Paramètres</p>
                    </a>

                    <div class="nav_sep"></div>

                    <a href="/logout" class="logout_a">
                        <span class="material-symbols-rounded nav_icon">logout</span>
                        <p class="semibold">Déconnexion</p>
                    </a>

                </div>
            </div>
        <?php else : ?>
            <!-- <a href="/login">
                <span class="material-symbols-rounded nav_icon">account_box</span>
                <p class="semibold nav_item__p"><?= $nick ?></p>
            </a> -->
            <div class="profile">
                <a href="/login">
                    <span class="material-symbols-rounded">person</span>
                </a>
            </div>
        <?php endif; ?>

    </div>
</nav>


<?php if (isset($_SESSION['auth'])) : ?>
    <script>
        const profile = document.querySelector('.profile');
        const profileMenu = document.querySelector('.profile_menu');

        // si on clique en dehors du menu, on le ferme, sinon on clique sur le menu, on l'ouvre
        document.addEventListener('click', (e) => {
            if (!profile.contains(e.target)) {
                profileMenu.classList.remove('profile_menu--active');
            } else if (e.target == profileMenu ) {
                return;
            } else {
                profileMenu.classList.toggle('profile_menu--active');
            }
        })

    </script>
<?php endif; ?>