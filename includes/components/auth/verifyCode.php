<?php

if (isset($_GET['error'])) {

    echo '<div class="signup--error">
    <p>' . $_SESSION['auth']['message'] . '</p>
    </div>';

    echo '<script>
    setTimeout(() => {
        document.querySelector(".signup--error").remove();
        window.location.href = "/login?method=verify";
    }, 3000);
    </script>';
}

$email = $_SESSION['auth']['email'];

// transforme le mail "abcxyz@gmail.com en "abc•••@gmail.com", c'est à dire en cachant tous les caractères avant le @ et en affichant seulement les 3 premiers caractères
// Séparer l'adresse email en nom d'utilisateur et domaine
$parts = explode("@", $email);
$username = $parts[0];
$domain = $parts[1];

// Cacher tous les caractères du nom d'utilisateur sauf les 3 premiers
if (strlen($username) <= 3) {
    $hiddenUsername = $username;
} else {
    $hiddenUsername = substr($username, 0, 3) . str_repeat("•", strlen($username) - 3);
}

// Reconstruire l'adresse email en utilisant le nom d'utilisateur caché et le domaine
$hiddenEmail = $hiddenUsername . "@" . $domain;

?>

<div class="card login">
    <p class="login__title">Vérification de l’email</p>

    <p class="secondary">Vous y êtes presque ! On a envoyé un email à
        <span class="important_info"><?= $hiddenEmail ?></span>. Veuillez rentrer le code à six chiffres partagé dans l’email pour valider votre inscription.
    </p>
    <br>

    <p class="secondary">Pensez à regarder dans les spams de votre boîte de réception.</p>
    <br>

    <form action="/auth" method="post" class="verifyCode_form">

        <div class="form__group code_form__group">
            <label for="n1">Code</label>
            <div class="input__container_wrapper">
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n1" data-pos="1">
                </div>
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n2" data-pos="2">
                </div>
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n3" data-pos="3">
                </div>
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n4" data-pos="4">
                </div>
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n5" data-pos="5">
                </div>
                <div class="input__container">
                    <input type="number" name="code[]" maxlength="1" min="0" max="9" class="code-input" placeholder="•" autocomplete="off" id="n6" data-pos="6">
                </div>
            </div>
        </div>

        <p class="resendCode_p">Vous ne trouvez pas le code ? <button class="resendcode_btn underline important_info" id="js-resendcode_btn">Renvoyer le code</button></p>
        <input type="radio" name="resendcode" id="js-resendcode_input" form="form_resend" class="hide">

        <button type="submit" class="play__btn login_btn login_btn--disabled" disabled>VALIDER</button>

        <p class="other_login_method">Besoin d'aide ? <a href="/need-help" class="important_info underline">Contactez-nous</a></p>
    </form>

    <!-- second formulaire pour le bouton "renvoyer le code" -->
    <form action="/auth" method="post" id="form_resend"></form>
</div>

<script>
    // comme il n'est pas possible de mettre un maxlength sur un input de type number, je le fais en JS
    const codeInputs = document.querySelectorAll('.code-input');
    const inputContainer = document.querySelectorAll('.input__container');
    const resendcode_btn = document.querySelector('#js-resendcode_btn');
    const resendcode_input = document.querySelector('#js-resendcode_input');
    const verifyCode_form = document.querySelector('.verifyCode_form');
    const form_resend = document.querySelector('#form_resend');
    const n1 = document.querySelector('#n1');

    // je focus sur le premier input à l'ouverture de la page
    n1.focus();

    codeInputs.forEach(input => {
        input.addEventListener('input', e => {
            if (e.target.value.length > e.target.maxLength) {
                e.target.value = e.target.value.slice(0, e.target.maxLength);
            }

            if (e.target.value.length == e.target.maxLength) {
                const nextInput = e.target.parentElement.nextElementSibling;
                if (nextInput) {
                    nextInput.children[0].focus();
                }
            }
        });
    });

    // si j'efface un input, je vais au précédent
    codeInputs.forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && e.target.value.length == 0) {
                const previousInput = e.target.parentElement.previousElementSibling;
                if (previousInput) {
                    previousInput.children[0].focus();
                }
            }
        });
    });

    // si tous les inputs sont remplis, je peux valider
    codeInputs.forEach(input => {
        input.addEventListener('input', e => {
            const loginBtn = document.querySelector('.login_btn');
            const codeInputs = document.querySelectorAll('.code-input');
            let isDisabled = false;

            codeInputs.forEach(input => {
                if (input.value.length == 0) {
                    isDisabled = true;
                }
            });

            if (isDisabled) {
                loginBtn.classList.add('login_btn--disabled');
                loginBtn.disabled = true;
            } else {
                loginBtn.classList.remove('login_btn--disabled');
                loginBtn.disabled = false;
            }
        });
    });

    // si je clique sur un inputContainer, je vais dans l'input
    inputContainer.forEach(container => {
        container.addEventListener('click', e => {
            if (e.target.classList.contains('input__container')) {
                e.target.children[0].focus();
            }
        });
    });

    // si je clique sur resendcode_btn, je valide le form avec l'input radio caché pour ré-envoyer un code au mail
    resendcode_btn.addEventListener('click', e => {
        e.preventDefault();
        console.log('click');
        resendcode_input.checked = true;
        form_resend.submit();
    });

    // si on ctrl + v dans un des inputs, on colle tout le résultat dans chaque input
    // ex : si on copie 123456, on va avoir 1 dans le premier input, 2 dans le second, etc...
    // seulement si tout le contenu est un nombre
    codeInputs.forEach(input => {
        input.addEventListener('paste', e => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const isPasteAllNumbers = /^\d+$/.test(paste);

            if (isPasteAllNumbers) {
                // le texte collé ne contient que des chiffres, on peut continuer
                const pasteArray = paste.split('');
                const inputs = document.querySelectorAll('.code-input');

                inputs.forEach((input, index) => {
                    input.value = pasteArray[index];
                });

                // à la fin, on submit le form
                verifyCode_form.submit();
            } else {
                // le texte collé contient des caractères autres que des chiffres, on affiche un message d'erreur
                alert('Le texte collé ne doit contenir que des chiffres');
            }
        });
    });
</script>
