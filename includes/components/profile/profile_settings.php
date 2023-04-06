<?php

if (isset($_SESSION['error'])) {
    if ($_SESSION['error'] == 'error') {
        echo
        '<div class="save--code save--error">
            <p>Une erreur est survenue lors de la modification</p>
        </div>';
    } else if ($_SESSION['error'] == 'success') {
        echo
        '<div class="save--code save--success">
            <p>Modification effectuée</p>
        </div>';
    }

    echo
    '<script>
        setTimeout(() => {
            document.querySelector(".save--code").remove();
        }, 3000);
    </script>';

    unset($_SESSION['error']);
}

?>

<h1 class="section_title">Paramètres du compte</h1>

<div class="main_wrapper">

    <section class="profile_page card">

        <div class="profile_picture__container">
            <img src="<?= $big_profile_pic ?>" class="profile_picture card" alt="Photo de profil">

            <div class="change_profile_pic_container">
                <div class="change_profile_pic__btn">
                    <span class="material-symbols-rounded">photo_camera</span>
                </div>
            </div>

            <!-- BOUTON DE SAUVEGARDE -->
            <div class="save_buttons__wrapper">
                <button class="btn" onclick="window.location.href = '/profile/<?= $user_result['pseudo'] ?>'">Annuler</button>
                <button class="btn" id="js-save" form="form1">Sauvegarder</button>
            </div>
        </div>


        <div class="profile_settings__container">

            <div class="settings_category" id="settings_info">
                <div class="settings_category__setting_wrapper setting_wrapper">
                    <div class="settings_category__title-container">
                        <p class="settings_category__title" id="profile_data">Pseudonyme</p>
                    </div>


                    <div class="form__group">
                        <div class="username_visible_container">
                            <p>thesesviz.test/profile/</p>
                        </div>
                        <div class="input__container">
                            <input form="form1" type="text" name="username" spellcheck="false" value="<?= $user_result['pseudo'] ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- -------------------------- SEPARATEUR -------------------------- -->
            <div class="profile_category_sep"></div>

            <div class="settings_category" id="settings_password">
                <div class="settings_category__setting_wrapper setting_wrapper">
                    <div class="settings_category__title-container">
                        <p class="settings_category__title" id="profile_data">Mot de passe</p>
                    </div>

                    <div class="settings_category__password_wrapper">

                        <div class="form__group_container-wrapper">
                            <div class="form__group_container">
                                <p>Ancien mot de passe</p>
                                <div class="form__group">
                                    <div class="input__container">
                                        <input form="form2" type="password" id="oldpassword" name="oldpassword" autocomplete="false" spellcheck="false">
                                    </div>
                                </div>
                            </div>

                            <div class="form__group_container">
                                <p>Nouveau mot de passe</p>
                                <div class="form__group">
                                    <div class="input__container_wrapper password_checking__wrapper">
                                        <div class="input__container">
                                            <input form="form2" type="password" id="newpassword" name="newpassword" autocomplete="false" spellcheck="false" minlength="12">
                                        </div>
                                        <span class="material-symbols-rounded verify_icon password_verify_icon">Check</span>

                                        <div class="password_checking__container card">
                                            <div class="password_checking">
                                                <p class="strength_label">Doit contenir 12 caractères minimum</p>
                                                <div class="strength_wrapper">
                                                    <div class="strength_bar"></div>
                                                    <div class="strength_bar"></div>
                                                    <div class="strength_bar"></div>
                                                    <div class="strength_bar"></div>
                                                </div>
                                                <p>Règles à respecter :</p>
                                                <div class="security_wrapper">
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Lettres minuscules</p>
                                                    </div>
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Lettres majuscules</p>
                                                    </div>
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Usage de Chiffres</p>
                                                    </div>
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Usage de caractères spéciaux</p>
                                                    </div>
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Ne pas contenir d'espace</p>
                                                    </div>
                                                    <div class="security_wrapper__item">
                                                        <span class="material-symbols-rounded security_icon">Check</span>
                                                        <p>Ne pas contenir le pseudo ou l’email</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="password__btn_container">
                            <button form="form2" class="btn btn--disabled" id="js-password_save" disabled>Modifier</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- -------------------------- SEPARATEUR -------------------------- -->
            <div class="profile_category_sep"></div>

            <div class="settings_category" id="settings_email">
                <div class="settings_category__setting_wrapper setting_wrapper">
                    <div class="settings_category__title-container">
                        <p class="settings_category__title" id="profile_data">Email</p>
                    </div>


                    <div class="settings_category__password_wrapper">
                        <div class="form__group">
                            <div class="input__container">
                                <input form="form3" type="text" name="newemail" spellcheck="false" id="newemail" value="<?= $user_result['email'] ?>">
                            </div>
                        </div>

                        <div class="verified_email__container">

                            <div class="verified_email__wrapper">
                                <?php if ($user_result['auth'] != 1) : ?>
                                    <div class="verified_email">
                                        <p>Email non vérifié. <a class="resend_a"><span class="bold primary">Cliquez ici pour le vérifier</span></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- resendcode -->
                            <div class="password__btn_container">
                                <button form="form3" class="btn btn--disabled" id="js-email_save" disabled>Modifier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- -------------------------- SEPARATEUR -------------------------- -->
            <div class="profile_category_sep"></div>

            <div class="settings_category" id="settings_account">
                <div class="settings_category__setting_wrapper setting_wrapper">
                    <div class="settings_category__title-container">
                        <p class="settings_category__title" id="profile_data">Compte</p>
                    </div>

                    <div class="settings_category__password_wrapper">

                        <p>Suppression du compte. Une confirmation sera demandée.</p>

                        <div class="password__btn_container" style="margin-bottom: 60px;">
                            <button class="btn" id="delete_account__btn">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="delete_account">
            <div class="delete_account__modal card">
                <div class="delete_account__title">
                    <p class="bolder">Suppression du compte</p>
                </div>

                <div class="delete_account__text">
                    <p class="bold">
                        Voulez-vous vraiment supprimer votre compte ? Cette <br>
                        action est <span class="primary">irréversible</span>. Votre profil et toutes vos données <br>
                        sur vos parties seront supprimées.
                    </p>
                </div>

                <div class="delete_account_buttons__wrapper">
                    <button class="btn" id="cancel_delete__btn">Annuler</button>
                    <button form="form4" class="btn" id="confirm_delete__btn">Sauvegarder</button>
                </div>
            </div>
        </div>



        <!-- formulaire des images du profil -->
        <form action="/editAccount" method="post" autocomplete="off" id="form1" enctype="multipart/form-data" class="hide">
            <input type="file" name="banner_image" id="banner_image" accept="image/*" onchange="preview(profile_banner)" enctype="multipart/form-data">
            <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="preview(profile_picture)" enctype="multipart/form-data">
            <input type="radio" name="delete_image" id="delete_image">
        </form>

        <!-- formulaire de mot de passe -->
        <form action="/editAccount" method="post" autocomplete="off" id="form2" class="hide">
        </form>

        <!-- formulaire de l'email -->
        <form action="/editAccount" method="post" autocomplete="off" id="form3" class="hide">
        </form>

        <!-- formulaire de la suppression du compte -->
        <form action="/editAccount" method="post" autocomplete="off" id="form4" class="hide">
            <input type="radio" name="deleteAccount" id="deleteAccount">
        </form>

        <!-- formulaire du renvoi de l'email de vérification -->
        <form action="/auth" method="post" autocomplete="off" id="form5" class="hide">
            <input type="radio" name="resendcode" id="resendcode">
        </form>

    </section>

</div>

<script>
    function auto_grow(element) {
        element.style.height = 'fit-content';
        element.style.height = ((element.scrollHeight) + 2) + "px";
    }

    // formulaire des images du profil
    const form1 = document.querySelector('#form1');
    // formulaire de mot de passe
    const form2 = document.querySelector('#form2');
    // formulaire de l'email
    const form3 = document.querySelector('#form3');
    // formulaire de la suppression du compte
    const form4 = document.querySelector('#form4');

    const save = document.querySelector('#save');
    const password_save = document.querySelector('#js-password_save');
    const email_save = document.querySelector('#js-email_save');
    const confirm_delete__btn = document.querySelector('#confirm_delete__btn');
    const deleteAccount = document.querySelector('#deleteAccount');
    const resend_a = document.querySelector('.resend_a');

    <?php if ($user_result['auth'] != 1) : ?>
        resend_a.addEventListener('click', () => {
            resendcode.checked = true;
            form5.submit();
        });
    <?php endif; ?>

    confirm_delete__btn.addEventListener('click', () => {
        deleteAccount.checked = true;
        form4.submit();
    });

    const newemail = document.querySelector('#newemail');

    // si newemail est différent de l'email actuel, on active le bouton de sauvegarde
    newemail.addEventListener('input', () => {
        if (newemail.value != '<?= $user_result['email'] ?>') {
            email_save.classList.remove('btn--disabled');
            email_save.disabled = false;
        } else {
            email_save.classList.add('btn--disabled');
            email_save.disabled = true;
        }
    });


    const cancel_delete__btn = document.querySelector('#cancel_delete__btn');

    cancel_delete__btn.addEventListener('click', () => {
        delete_account.classList.remove('delete_account--active');
    });

    // ces 3 là sont des inputs des forms du dessus
    const banner_image = document.querySelector('#banner_image');
    const profile_image = document.querySelector('#profile_image');
    const delete_image = document.querySelector('#delete_image');

    const change_profile_pic__btn = document.querySelector('.change_profile_pic__btn');

    change_profile_pic__btn.addEventListener('click', () => {
        // console.log('change profile pic');
        profile_image.click();
    });


    const verified_email__wrapper = document.querySelector('.verified_email__wrapper');
    verified_email__wrapper.style.height = verified_email__wrapper.parentElement.offsetHeight + 'px';

    const verified_email = document.querySelector('.verified_email');

    // PHP rajoute un espace vide dans le DOM si l'élement n'existe pas, on le supprime donc pour éviter d'avoir un espace vide entre le div pour permettre de cacher l'élément en css
    if (verified_email == undefined) {
        verified_email__wrapper.innerHTML = '';
    }

    const delete_account = document.querySelector('.delete_account');
    const delete_account__btn = document.querySelector('#delete_account__btn');

    delete_account__btn.addEventListener('click', () => {
        delete_account.classList.add('delete_account--active');
    });


    // ON VERIFIE LE MOT DE PASSE AVEC LES REGLES (copié collé de sign_up.php (modif un peu quand même))
    const password = document.querySelector("#newpassword");
    const password_confirm = document.querySelector("#oldpassword");
    const password_checking__container = document.querySelector(".password_checking__container");

    let password_test = false;
    const passwordInputs = [password];

    passwordInputs.forEach(input => {
        input.addEventListener("input", () => {
            if (input.value.length > 0 || input.value.length === 0) {
                validatePassword(password);
            }
        })
    });

    // si password est focus, on ajoute la classe active au password_checking__container
    password.addEventListener("focus", () => {
        password_checking__container.classList.add("password_checking__container--active");
    })

    // si password n'est pas focus, on enlève la classe active du password_checking__container
    password.addEventListener("blur", () => {
        password_checking__container.classList.remove("password_checking__container--active");
    })

    const passwordVerifyIcon = document.querySelector(".password_verify_icon");
    const passwordConfirmVerifyIcon = document.querySelector(".password_confirm_verify_icon");

    function verifyPasswordIcon(good) {
        if (good == 'good') {
            passwordVerifyIcon.style.color = "green";
            passwordVerifyIcon.style.visibility = "visible";
            passwordVerifyIcon.textContent = "Check";
            passwordVerifyIcon.setAttribute("title", "Le mot de passe est valide");
        } else {
            passwordVerifyIcon.style.color = "red";
            passwordVerifyIcon.style.visibility = "visible";
            passwordVerifyIcon.textContent = "Clear";
            passwordVerifyIcon.setAttribute("title", "Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial");
        }
    }

    function validatePassword(passwordParam) {

        // on regarde le mot de passe principal
        // on regarde le mot de passe principal
        // on regarde le mot de passe principal
        // on regarde le mot de passe principal
        // on regarde le mot de passe principal

        // on vérifie :
        // si le mot de passe contient au moins 12 caractères
        // si le mot de passe contient au moins une minuscule
        // si le mot de passe contient au moins une majuscule
        // si le mot de passe contient au moins un chiffre
        // si le mot de passe contient au moins un caractère spécial
        // si le mot de passe ne contient pas d'espace
        // si le mot de passe ne contient pas le pseudo ou l'email

        let char12;
        let lowerCase;
        let upperCase;
        let number;
        let specialChar;
        let space;
        let nickOrMail;

        const strength_label = document.querySelector(".strength_label");
        const security_wrapper__item = document.querySelectorAll(".security_wrapper__item");
        const strength_bar = document.querySelectorAll(".strength_bar");

        // 12 caractères
        if (passwordParam === password) {
            if (passwordParam.value.length < 12) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                char12 = true;
            }
        }

        // minuscule
        if (passwordParam === password) {
            if (!/[a-z]/.test(passwordParam.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                lowerCase = true;
            }
        }

        // majuscule
        if (passwordParam === password) {
            if (!/[A-Z]/.test(passwordParam.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                upperCase = true;
            }
        }

        // chiffre
        if (passwordParam === password) {
            if (!/[0-9]/.test(passwordParam.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                number = true;
            }
        }

        // caractère spécial
        if (passwordParam === password) {
            if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(passwordParam.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                specialChar = true;
            }
        }

        // espace
        if (passwordParam === password) {
            if (/\s/.test(passwordParam.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else {
                space = true;
            }
        }


        const nick = '<?= $user_result['pseudo'] ?>';
        const mail = '<?= $user_result['email'] ?>';
        // pseudo ou email
        if (passwordParam === password) {
            if (passwordParam.value.includes(nick.value) || passwordParam.value.includes(mail.value)) {
                setTimeout(() => {
                    verifyPasswordIcon('bad');
                }, 300);
            } else if (!passwordParam.value.includes(nick.value) || !passwordParam.value.includes(mail.value)) {
                nickOrMail = true;
            }
        }

        // Tant que char12 n'est pas valide, on ne peut pas tester les autres conditions
        let nbTest = 0;
        if (char12) {
            if (lowerCase) {
                security_wrapper__item[0].classList.add("security_wrapper__item--checked");
                nbTest++;
            } else {
                security_wrapper__item[0].classList.remove("security_wrapper__item--checked");
            }
            if (upperCase) {
                security_wrapper__item[1].classList.add("security_wrapper__item--checked");
                nbTest++;
            } else {
                security_wrapper__item[1].classList.remove("security_wrapper__item--checked");
            }
            if (number) {
                security_wrapper__item[2].classList.add("security_wrapper__item--checked");
                nbTest++;
            } else {
                security_wrapper__item[2].classList.remove("security_wrapper__item--checked");
            }
            if (specialChar) {
                security_wrapper__item[3].classList.add("security_wrapper__item--checked");
                nbTest++;
            } else {
                security_wrapper__item[3].classList.remove("security_wrapper__item--checked");
            }
            if (space) {
                security_wrapper__item[4].classList.add("security_wrapper__item--checked");
            } else {
                security_wrapper__item[4].classList.remove("security_wrapper__item--checked");
            }
            if (nickOrMail) {
                security_wrapper__item[5].classList.add("security_wrapper__item--checked");
            } else {
                security_wrapper__item[5].classList.remove("security_wrapper__item--checked");
            }

        } else {
            security_wrapper__item.forEach(item => {
                item.classList.remove("security_wrapper__item--checked");
            })
        }

        if (nbTest == 0) {
            if (passwordParam.value.length > 0) {
                strength_label.innerHTML = "Doit contenir 12 caractères minimum <span style='font-size: 12px' class='bold primary'>" + passwordParam.value.length + "/12</span>";
            } else {
                strength_label.textContent = "Doit contenir 12 caractères minimum";
            }
            strength_bar[0].style.backgroundColor = "#D9D9D9";
            strength_bar[1].style.backgroundColor = "#D9D9D9";
            strength_bar[2].style.backgroundColor = "#D9D9D9";
            strength_bar[3].style.backgroundColor = "#D9D9D9";
        } else if (nbTest == 1) {
            strength_label.textContent = "Mot de passe faible";
            strength_bar[0].style.backgroundColor = "#B00000";
            strength_bar[1].style.backgroundColor = "#D9D9D9";
            strength_bar[2].style.backgroundColor = "#D9D9D9";
            strength_bar[3].style.backgroundColor = "#D9D9D9";
        } else if (nbTest == 2) {
            strength_label.textContent = "Mot de passe moyen";
            strength_bar[0].style.backgroundColor = "#FF6B00";
            strength_bar[1].style.backgroundColor = "#FF6B00";
            strength_bar[2].style.backgroundColor = "#D9D9D9";
            strength_bar[3].style.backgroundColor = "#D9D9D9";
        } else if (nbTest == 3) {
            strength_label.textContent = "Mot de passe fort";
            strength_bar[0].style.backgroundColor = "#F6CF00";
            strength_bar[1].style.backgroundColor = "#F6CF00";
            strength_bar[2].style.backgroundColor = "#F6CF00";
            strength_bar[3].style.backgroundColor = "#D9D9D9";
        } else if (nbTest == 4) {
            strength_label.textContent = "Mot de passe très fort";
            strength_bar[0].style.backgroundColor = "#008F06";
            strength_bar[1].style.backgroundColor = "#008F06";
            strength_bar[2].style.backgroundColor = "#008F06";
            strength_bar[3].style.backgroundColor = "#008F06";
        }

        if (char12 && lowerCase && upperCase && number && specialChar && space && nickOrMail) {
            setTimeout(() => {
                verifyPasswordIcon('good');
            }, 300);

            password_test = true;
            return;
        } else {
            setTimeout(() => {
                verifyPasswordIcon('bad');
            }, 300);

            password_test = false;
            return;
        }
    }

    const oldpassword = document.querySelector('#oldpassword');
    const newpassword = document.querySelector('#newpassword');
    const pass = [oldpassword, newpassword];

    // si les deux inputs sont remplis, on active le bouton de sauvegarde
    pass.forEach(input => {
        input.addEventListener('input', () => {
            if (oldpassword.value != '' && newpassword.value != '') {
                // password_save.classList.remove('btn--disabled');
                // password_save.disabled = false;
                if (password_test) {
                    password_save.classList.remove('btn--disabled');
                    password_save.disabled = false;
                } else {
                    password_save.classList.add('btn--disabled');
                    password_save.disabled = true;
                }
            } else {
                password_save.classList.add('btn--disabled');
                password_save.disabled = true;
            }
        });
    });
</script>