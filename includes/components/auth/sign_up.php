<?php

if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo '<div class="signup--error">
    <p>Une erreur est survenue lors de l\'inscription</p>
    </div>';

    echo '<script>
    setTimeout(() => {
        document.querySelector(".signup--error").remove();
        document.location.href = "/login?method=signup";
    }, 3000);
    </script>';
}

?>
<style>
    .input__container_wrapper {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .input__container_wrapper .input__container {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .input__container_wrapper .verify_icon {
        font-variation-settings:
            'FILL'0,
            'wght'700,
            'GRAD'0,
            'opsz'48;
        font-size: 24px;
        color: green;
        visibility: hidden;
    }
</style>

<div class="card login">
    <p class="login__title">Inscription</p>

    <form action="/auth" method="post" class="signup_form">


        <div class="form__group">
            <label for="nick">Pseudonyme <span class="secondary_c bold">*</span></label>
            <div class="input__container_wrapper">
                <div class="input__container">
                    <input type="text" id="nick" name="nick" autocomplete="true" required spellcheck="false" minlength="3" maxlength="15">
                </div>
                <span class="material-symbols-rounded verify_icon">Check</span>
            </div>
        </div>

        <div class="form__group">
            <label for="mail">Adresse email <span class="secondary_c bold">*</span></label>
            <div class="input__container_wrapper">
                <div class="input__container">
                    <input type="email" id="mail" name="mail" autocomplete="true" required spellcheck="false" minlength="3">
                </div>
                <span class="material-symbols-rounded verify_icon">Check</span>
            </div>
        </div>

        <div class="form__group">
            <label for="password">Mot de passe <span class="secondary_c bold">*</span></label>
            <div class="input__container_wrapper password_checking__wrapper">
                <div class="input__container">
                    <input type="password" id="password" name="password" autocomplete="true" required spellcheck="false" minlength="12">
                    <span class="material-symbols-rounded togglePassword">Visibility_Off</span>
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

        <div class="form__group">
            <label for="password_confirm">Confirmation du mot de passe <span class="secondary_c bold">*</span></label>
            <div class="input__container_wrapper">
                <div class="input__container">
                    <input type="password" id="password_confirm" autocomplete="true" required spellcheck="false" minlength="12">
                    <span class="material-symbols-rounded togglePassword">Visibility_Off</span>
                </div>
                <span class="material-symbols-rounded verify_icon password_confirm_verify_icon">Check</span>
            </div>
        </div>

        <input type="hidden" name="signup" checked>
        <button type="submit" class="btn login_btn login_btn--disabled" disabled>INSCRIPTION</button>


        <p class="other_login_method">Vous avez déjà un compte ? <a href="/login" class="important_info underline">Connectez-vous</a></p>
    </form>
</div>


<script>
    // si les deux champs nick_mail et password sont remplis, on enlève la classe disabled du bouton de connexion
    const inputs = document.querySelectorAll(".form__group input");
    const loginBtn = document.querySelector(".login_btn");
    const nick = document.querySelector("#nick");
    const mail = document.querySelector("#mail");
    const password = document.querySelector("#password");
    const password_confirm = document.querySelector("#password_confirm");
    const form = document.querySelector(".login");
    const password_checking__container = document.querySelector(".password_checking__container");
    let nick_test;
    let mail_test;
    let password_test = false;
    let confirm_password_test;

    const mainInputs = [nick, mail];
    const passwordInputs = [password, password_confirm];

    form.addEventListener("input", () => {

        if (form.timeout) {
            clearTimeout(form.timeout);
        }

        console.log(nick_test, mail_test, password_test, confirm_password_test);

        form.timeout = setTimeout(() => {

            if (nick.value.length > 0 && password.value.length > 0 && mail.value.length > 0 && password_confirm.value.length > 0 && nick_test && mail_test && password_test && confirm_password_test) {
                loginBtn.classList.remove("login_btn--disabled");
                loginBtn.disabled = false;
            } else {
                loginBtn.classList.add("login_btn--disabled");
                loginBtn.disabled = true;
            }
        }, 400);
    })

    const togglePassword = document.querySelectorAll('.togglePassword');

    password.addEventListener("input", () => {
        if (password.value.length > 0) {
            togglePassword[0].style.display = "block";
        } else {
            togglePassword[0].style.display = "none";
        }
    })

    togglePassword[0].addEventListener('click', function(e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        type === 'password' ? togglePassword[0].textContent = "Visibility_off" : togglePassword[0].textContent = "Visibility";
    });

    password_confirm.addEventListener("input", () => {
        if (password_confirm.value.length > 0) {
            togglePassword[1].style.display = "block";
        } else {
            togglePassword[1].style.display = "none";
        }
    })

    togglePassword[1].addEventListener('click', function(e) {
        const type = password_confirm.getAttribute('type') === 'password' ? 'text' : 'password';
        password_confirm.setAttribute('type', type);
        type === 'password' ? togglePassword[1].textContent = "Visibility_off" : togglePassword[1].textContent = "Visibility";
    });

    // vérification de la validité du pseudo en temps réel en ajax
    function verifyDataWithFetch(nameoption, option, optionValue) {

        const verifyIcon = nameoption.parentElement.nextElementSibling;
        let db_valid_mail;
        let db_valid_nick;

        fetch("/auth", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "ajax-check=true&" + option
            })
            .then(response => response.json())
            .then(data => {
                data_key = Object.keys(data)[0];
                data_value = Object.values(data)[0];

                // console.log(data_key, data_value, optionValue, optionValue.length);


                if (optionValue.length == 0) {
                    verifyIcon.style.visibility = "hidden";
                    verifyIcon.textContent = "error";

                    return;
                }

                // si optionValue.length est inférieur à 3, on affiche un message d'erreur
                if (optionValue.length < 3) {
                    verifyIcon.style.color = "var(--secondary)";
                    verifyIcon.style.visibility = "visible";
                    verifyIcon.textContent = "error";
                    verifyIcon.setAttribute("title", "Ce champ doit contenir au moins 3 caractères");

                    data_key == "nick" ? nick_test = false : '';
                    return;
                }

                if (data_key == "nick") {
                    data_key = "Ce pseudo";
                } else if (data_key == "mail") {
                    data_key = "Cet email";
                }

                // console.log('data_value - ' + data_value);
                if (data_value) {
                    verifyIcon.style.color = "green";
                    verifyIcon.style.visibility = "visible";
                    verifyIcon.textContent = "Check";
                    verifyIcon.setAttribute("title", data_key + " est valide");

                    if (data_key == "Ce pseudo") {
                        db_valid_nick = true;
                    } else if (data_key == "Cet email") {
                        db_valid_mail = true;
                    }

                } else {
                    verifyIcon.style.color = "red";
                    verifyIcon.style.visibility = "visible";
                    verifyIcon.textContent = "Clear";
                    verifyIcon.setAttribute("title", data_key + " est déjà utilisé");

                    if (data_key == "Ce pseudo") {
                        db_valid_nick = false;
                    } else if (data_key == "Cet email") {
                        db_valid_mail = false;
                    }
                }

                // si l'input ne respecte pas les conditions, on affiche un message d'erreur
                if (data_key == "Ce pseudo") {

                    let ValidateNick = validateNickname(optionValue);

                    if (!ValidateNick) {
                        verifyIcon.style.color = "var(--secondary)";
                        verifyIcon.style.visibility = "visible";
                        verifyIcon.textContent = "error";
                        verifyIcon.setAttribute("title", "Veuillez n'avoir que des lettres, chiffres ou underscore dans votre pseudo");
                    }

                    nick_test = db_valid_nick && ValidateNick;
                    return;
                }

                if (data_key == "Cet email") {

                    let ValidateMail = validateEmail(optionValue);

                    if (!ValidateMail) {
                        verifyIcon.style.color = "var(--secondary)";
                        verifyIcon.style.visibility = "visible";
                        verifyIcon.textContent = "error";
                        verifyIcon.setAttribute("title", "Ce format d'email n'est pas valide");
                    }

                    mail_test = db_valid_mail && ValidateMail;
                    return;
                }

            })
    }

    // vérification de la validité du pseudo
    function validateNickname(nickname) {
        // le pseudo ne peut contenir que des lettres, chiffres et _
        let re = /^[\w_]+$/;
        return re.test(nickname);
    }

    // vérification de la validité de l'email en regex pour avoir un email valide (@ et .)
    function validateEmail(email) {
        let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    mainInputs.forEach(input => {
        input.addEventListener("keyup", (e) => {
            if (input.value.length > 0) {
                // on évite de faire la requête ajax plusieurs fois si l'utilisateur tape trop vite
                if (input.timeout) {
                    clearTimeout(input.timeout);
                }

                input.timeout = setTimeout(() => {
                    verifyDataWithFetch(input, input.name + "=" + input.value, input.value);
                }, 300);

            }

            // si le key est ctrl, on ne fait rien
            if (e.key === "Control") {
                return;
            }
        })
    })

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            if (input.value.length === 0) {
                input.parentElement.nextElementSibling.style.visibility = "hidden";
            }
        })
    })

    passwordInputs.forEach(input => {
        input.addEventListener("input", () => {
            if (input.value.length > 0 || input.value.length === 0) {
                validatePassword(password);
                validateConfirmPassword();
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

    function validateConfirmPassword() {
        // on regarde le mot de passe de confirmation
        // on regarde le mot de passe de confirmation
        // on regarde le mot de passe de confirmation
        // on regarde le mot de passe de confirmation
        // on regarde le mot de passe de confirmation

        if (password.value == password_confirm.value && password.value.length > 0) {
            if (password_test) {
                confirm_password_test = true;
            } else {
                confirm_password_test = false;
            }
        } else {
            confirm_password_test = false;
        }

        if (confirm_password_test && password_confirm.value.length > 0) {

            setTimeout(() => { // ajout de délai virtuel pour ne pas dénaturer l'expérience utilisateur car les requetes ajax sont retardées pour éviter de faire trop de requetes
                passwordConfirmVerifyIcon.style.color = "green";
                passwordConfirmVerifyIcon.style.visibility = "visible";
                passwordConfirmVerifyIcon.textContent = "Check";
                passwordConfirmVerifyIcon.setAttribute("title", "Les mots de passe correspondent");
            }, 200);

        } else if (!confirm_password_test && password_confirm.value.length > 0) {

            setTimeout(() => { // pareil ici
                passwordConfirmVerifyIcon.style.color = "red";
                passwordConfirmVerifyIcon.style.visibility = "visible";
                passwordConfirmVerifyIcon.textContent = "Clear";
                passwordConfirmVerifyIcon.setAttribute("title", "Les mots de passe ne correspondent pas");
            }, 200);
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
</script>