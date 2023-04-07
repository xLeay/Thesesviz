<?php
// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";


require_once ROOT . '/includes/classes/db.class.php';

$db = new DB();
$conn = $db->cnx();

// SI AUCUN PSEUDO, REDIRECTION VERS LA PAGE LOGIN
if (!isset($_SESSION['auth']['pseudo']) && $_SERVER['REQUEST_URI'] !== '/login') {
    echo '<script>window.location.href = "/login";</script>';
}

$big_profile_pic = increaseIdenticonSize($profil_pic, 150);

$visible_profile = htmlspecialchars(explode('/', $_SERVER['REQUEST_URI'])[2]);


if (isset($_SESSION['auth']['pseudo']) && $_SESSION['auth']['pseudo'] === $visible_profile) {
    $pseudo = htmlspecialchars($_SESSION['auth']['pseudo']);
    $email = htmlspecialchars($_SESSION['auth']['email']);

    $sql = "SELECT pseudo, mdp, email, photo_profil, auth FROM utilisateur WHERE pseudo = :pseudo OR email = :email";
    $selection = $conn->prepare($sql);
    $selection->bindParam(':pseudo', $pseudo);
    $selection->bindParam(':email', $email);
    $selection->execute();

    $user_result = $selection->fetch(PDO::FETCH_ASSOC);

    // debug($user_result);
    // debug($big_profile_pic);

} else if (isset($_SESSION['auth']['pseudo']) && $_SESSION['auth']['pseudo'] !== $visible_profile && (stripos($_SERVER['REQUEST_URI'], '/profile/') !== false && stripos($_SERVER['REQUEST_URI'], '/settings') === false)) {
    $sql = "SELECT pseudo, bio, email, photo_profil FROM utilisateur WHERE pseudo = :pseudo";
    $selection = $conn->prepare($sql);
    $selection->bindParam(':pseudo', $visible_profile);
    $selection->execute();

    $user_result = $selection->fetch(PDO::FETCH_ASSOC);


    $profil_pic = generateIdenticon($user_result['email']);
    $big_profile_pic = increaseIdenticonSize($profil_pic, 150);

    // debug($user_result);
    // debug($big_profile_pic);
}

 
// debug($user_result);
?>

<div class="main_container">

    <!-- Si l'url contient '/profile/' ET ne contient pas '/settings', alors on affiche le profil -->
    <?php if (stripos($_SERVER['REQUEST_URI'], '/profile/') !== false && stripos($_SERVER['REQUEST_URI'], '/settings') === false && stripos($_SERVER['REQUEST_URI'], '/alerts') === false) : ?>
        <?php include_once ROOT . '/includes/components/profile/visible_profile.php' ?>

        <!-- Si l'url contient '/profile/' ET contient '/settings', alors on affiche les paramètres du compte -->
    <?php elseif (stripos($_SERVER['REQUEST_URI'], '/profile/') !== false && stripos($_SERVER['REQUEST_URI'], '/settings') !== false) : ?>
        <?php include_once ROOT . '/includes/components/profile/profile_settings.php' ?>

        <!-- Si l'url contient '/profile/' ET contient '/alerts', alors on affiche les paramètres du compte -->
    <?php elseif (stripos($_SERVER['REQUEST_URI'], '/profile/') !== false && stripos($_SERVER['REQUEST_URI'], '/alerts') !== false) : ?>
        <?php include_once ROOT . '/includes/components/profile/profile_alerts.php' ?>

    <?php endif; ?>
</div>

<script>
    function preview(img) {
        // si img n'existe pas, on le crée
        if (!img) {
            // img = document.createElement('img');
            // img.src = URL.createObjectURL(event.target.files[0]);
            profile_banner__container.innerHTML = `<img src="${URL.createObjectURL(event.target.files[0])}" class="profile_banner" alt="Bannière du profil">`;
        } else {
            img.src = URL.createObjectURL(event.target.files[0]);
        }
    }

    // Fonction pour avoir la couleur moyenne d'une image
    const getAverageColor = (img) => {
        const max = 10; // Max size (Higher num = better precision but slower)
        const {
            naturalWidth: iw,
            naturalHeight: ih
        } = img;
        const ctx = document.createElement `canvas`.getContext `2d`;
        const sr = Math.min(max / iw, max / ih); // Scale ratio
        const w = Math.ceil(iw * sr); // Width
        const h = Math.ceil(ih * sr); // Height
        const a = w * h; // Area

        img.crossOrigin = 1;
        ctx.canvas.width = w;
        ctx.canvas.height = h;
        ctx.drawImage(img, 0, 0, w, h);

        const data = ctx.getImageData(0, 0, w, h).data;
        let r = g = b = 0;

        for (let i = 0; i < data.length; i += 4) {
            r += data[i];
            g += data[i + 1];
            b += data[i + 2];
        }

        r = ~~(r / a);
        g = ~~(g / a);
        b = ~~(b / a);

        return {
            r,
            g,
            b
        };
    };

    const profile_banner__container = document.querySelector('.profile_banner__container');
    const profile_banner = document.querySelector('.profile_banner');
    const profile_picture = document.querySelector('.profile_picture.card');
    let profile_images = null;
    let imageUrls = null;

    profile_images = [profile_picture];
    imageUrls = [
        profile_picture.src
    ];

    let imagesAvgColors = {
        profile_banner_color: {
            r: 0,
            g: 0,
            b: 0
        },
        profile_picture_color: {
            r: 0,
            g: 0,
            b: 0
        }
    }

    // On attends que les images soient chargées pour avoir leur couleur moyenne
    // Source - StackOverflow : https://stackoverflow.com/a/73326001/17717770
    // imageUrls.map returns Promise array (because callback function is async), 
    // which is immediately passed to Promise.all which accumulates them into one
    // promise which will be resolved when all its elements will finish.
    Promise.all(
        imageUrls.map(async (imageUrls, i) => {
            const tmpImage = new Image();

            // Create synchronization promise
            const syncPromise = new Promise((resolve) => {

                tmpImage.onload = (e) => {
                    // Handle loaded image
                    // console.log("Finished loading image: ", i, imageUrls);

                    // Get average color
                    const {
                        r,
                        g,
                        b
                    } = getAverageColor(tmpImage);
                    profile_images.forEach(img => {
                        if (img.src == imageUrls) {
                            img.style.backgroundColor = `rgba(${r},${g},${b},0.7)`;
                        }

                        imagesAvgColors[img.className + '_color'] = {
                            r,
                            g,
                            b
                        }
                    });

                    // Don't forget to resolve syncPromise
                    resolve();
                };

                tmpImage.src = imageUrls;
            });

            // Wait for syncPromise (image loaded)
            await syncPromise;
        })
    ).then(() => {});
</script>