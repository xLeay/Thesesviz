<?php

// On sélectionne toutes les alertes de l'utilisateur
$sql = "SELECT u.alertes FROM utilisateur u WHERE id_user = :id_user";
$alertes = $conn->prepare($sql);
$alertes->bindParam(':id_user', $_SESSION['auth']['id_user']);
$alertes->execute();

// On récupère les résultats
$alertes_result = $alertes->fetchAll(PDO::FETCH_ASSOC);

// On récupère les alertes dans un tableau
$alertes_array = explode(',', $alertes_result[0]['alertes']);

?>
<img src="<?= $big_profile_pic ?>" class="profile_picture card hide" alt="Photo de profil">


<h1 class="section_title" style="margin-bottom: 10px">Gestion des alertes</h1>

<div class="main_wrapper">
    <section class="profile_page card">
        <div class="profile_info__container">
            <div class="profile_info__user">
                <p class="profile_name" id="profile_info"><?= $user_result['pseudo'] ?></p>
            </div>
        </div>

        <?php if (count($alertes_array) > 0) : ?>
            <div class="alerts-container">
                <?php foreach ($alertes_array as $alerte) : ?>
                    <?php if ($alerte == '') continue; ?>
                    <div class="card alert-item">
                        <p><?= $alerte ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Aucune alerte mise en place sur le compte.</p>
        <?php endif; ?>

        <div class="form_container"></div>
        <button class="btn add-btn">Ajouter</button>
        <button class="btn alert-btn" form="form1">Appliquer</button>
    </section>
    <form action="/editAccount" class="hide" id="form1" method="post"></form>
</div>


<script>
    const alertBtn = document.querySelector('.alert-btn');
    const addBtn = document.querySelector('.add-btn');

    addBtn.addEventListener('click', (e) => {

        e.preventDefault();
        // On crée un élément input de type 'text'
        const form__group = document.createElement('div');
        form__group.classList.add('form__group');
        form__group.innerHTML = `
            <div class="input__container">
                <input form="form1" type="text" name="alert" spellcheck="false" id="alert" placeholder="Ajoutez une alerte">
            </div>
        `;
        document.querySelector('.form_container').appendChild(form__group);

    });

    alertBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const form = document.querySelector('#form1');
        form.submit();
    });
</script>