<div class="card login">
    <p class="login__title">Connexion</p>

    <form action="/auth" method="post">

        <div class="form__group">
            <label for="nick_mail">Pseudonyme ou adresse email</label>
            <div class="input__container">
                <input type="text" id="nick_mail" name="nick_mail" autocomplete="true" required spellcheck="false">
            </div>
        </div>

        <div class="form__group">
            <label for="password">Mot de passe</label>
            <div class="input__container">
                <input type="password" id="password" name="password" autocomplete="true" required spellcheck="false">
                <span class="material-symbols-rounded" id="togglePassword">Visibility_Off</span>
            </div>
        </div>

        <div class="form__group remember_me__group">
            <div class="remember_me">


                <div class="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"></label>
                </div>
                <label for="remember">Rester connecté(e)</label>


            </div>
            <a href="/forgot-password" class="primary">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn login_btn login_btn--disabled" disabled>CONNEXION</button>

        <p class="other_login_method">Vous n’avez pas de compte ? <a href="/login?method=signup" class="important_info underline">Inscrivez-vous</a></p>
    </form>
</div>


<script>
    // si les deux champs nick_mail et password sont remplis, on enlève la classe disabled du bouton de connexion
    const loginBtn = document.querySelector(".login_btn");
    const nickMail = document.querySelector("#nick_mail");
    const password = document.querySelector("#password");
    const form = document.querySelector(".login");

    form.addEventListener("input", () => {
        if (nickMail.value.length > 0 && password.value.length > 0) {
            loginBtn.classList.remove("login_btn--disabled");
            loginBtn.disabled = false;
        } else {
            loginBtn.classList.add("login_btn--disabled");
            loginBtn.disabled = true;
        }
    })

    const togglePassword = document.querySelector('#togglePassword');
    password.addEventListener("input", () => {
        if (password.value.length > 0) {
            togglePassword.style.display = "block";
        } else {
            togglePassword.style.display = "none";
        }
    })

    togglePassword.addEventListener('click', function(e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        type === 'password' ? togglePassword.textContent = "Visibility_off" : togglePassword.textContent = "Visibility";
    });
</script>