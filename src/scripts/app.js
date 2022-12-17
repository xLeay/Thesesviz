

function truncate(text, maxLength, dotCount) {
    let modText = text.trim();
    if (modText.length > maxLength) {
        modText = text.substring(0, maxLength - dotCount);
        modText = modText.padEnd(maxLength, ".");
        return modText;
    }
    return text;
}

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

document.addEventListener('DOMContentLoaded', function () {

    const search_thesis = document.querySelector('.search_thesis');
    const search_icon = document.querySelector('.search_icon');
    const search_icon_parent = document.querySelector('.search__icon');
    const search_options = document.querySelector('.search_options');
    const search_option_item = document.querySelectorAll('.search_options__item');
    const search_input = document.getElementById('search_input');
    const quick_thesis_academy = document.querySelectorAll('.quick_thesis_academy a');

    const backArrow = document.getElementById('backArrow');


    const primary = getComputedStyle(document.body).getPropertyValue('--primary');
    const secondary = getComputedStyle(document.body).getPropertyValue('--secondary');
    const placeholder = document.querySelector('.search__editor-placeholder-inner');


    let referrer = document.referrer;
    backArrow.setAttribute('href', referrer);
    backArrow.onclick = function () {
        window.history.back();
        return false;
    };

    // si l'uri == '/', on supprime la flèche de retour
    if (window.location.pathname != '/') {
        backArrow.style.visibility = 'visible';
    }

    quick_thesis_academy.forEach(function (item) {

        let liste = ['Université', 'université', 'University', 'Università'];
        liste.forEach(function (item2) {
            if (item.textContent.includes(item2)) {
                item.textContent = item.textContent.replace(item2, '');
            }
        });

        // on supprime les espaces en trop
        item.textContent = item.textContent.trim();

        // on met la première lettre en majuscule
        item.textContent = item.textContent.charAt(0).toUpperCase() + item.textContent.slice(1);
    });

    placeholder.addEventListener('input', function () {
        showPlaceholder();
        delSpan();
        detectKeyword();
    });

    placeholder.addEventListener('keydown', function (e) {
        if (e.keyCode === 13) {

            // prevent default action
            e.preventDefault();
            // trigger search
            getContent();

            // change input name based on search option
            setInputname();

            search_thesis.submit();
        }

        // si on fait la touche escape, on cache les options de recherche
        if (e.keyCode === 27) {
            search_options.style.display = 'none';
        }

    });

    // On affiche les options de recherche lorsqu'on clique sur le formulaire de recherche
    document.addEventListener('click', function (e) {
        if (e.target == search_thesis || search_thesis.contains(e.target) && e.target != search_options && search_options.contains(e.target) == false) {

            placeholder.focus();
            search_options.style.display = 'flex';

        } else {
            search_options.style.display = 'none';
        }
    });

    search_options.addEventListener('click', function (e) {
        search_option_item.forEach(function (item) {
            if (item.contains(e.target)) {

                placeholder.attributes.placeholder.value = '';

                createSpanOption(e.target.parentElement.querySelector('span').textContent);
                showPlaceholder();
                search_options.style.display = 'none';
            }
        });
    });

    const scroll_to_top = document.querySelector('.scroll_to_top');
    // on affiche le bouton de scroll to top quand on scroll
    window.addEventListener('scroll', function () {
        if (window.scrollY > 100) {
            scroll_to_top.classList.add('show');
        } else {
            scroll_to_top.classList.remove('show');
        }
    });

    // Place le curseur à la fin du contenu editable
    function setEndOfContenteditable(elem) {
        let sel = window.getSelection();
        sel.selectAllChildren(elem);
        sel.collapseToEnd();
    }

    // Affiche le placeholder et gère l'icone de fermeture
    function showPlaceholder() {
        if (placeholder.innerHTML != '') {

            placeholder.classList.add('write');
            placeholder.attributes.placeholder.value = '';

            search_icon.innerHTML = 'close';
            search_icon_parent.classList.add('close');
            const close_icon = document.querySelector('.close');

            close_icon.addEventListener('click', function () {
                placeholder.classList.remove('write');
                placeholder.innerHTML = '';
                placeholder.attributes.placeholder.value = 'Recherche de thèse...';
                search_icon.innerHTML = 'search';
                search_icon_parent.classList.remove('close');
            });

        } else {
            placeholder.classList.remove('write');
            placeholder.attributes.placeholder.value = 'Recherche de thèse...';

            search_icon.innerHTML = 'search';
            search_icon_parent.classList.remove('close');
        }
    }

    // fonction qui supprime les span en entier lorsqu'on supprime le premier caractère du span
    function delSpan() {
        let span = document.querySelectorAll('.search__editor-option');
        nodes = placeholder.childNodes;
        span.forEach(function (item) {
            if (nodes.length == 1) {
                item.remove();
                showPlaceholder();
            }

        });
    }

    // fonction qui retour le résultat de la recherche car une div en contenteditable ne peut pas être utilisé dans un formulaire
    function getContent() {
        search_input.value = placeholder.textContent;
        let option = null;
        if (placeholder.querySelector('span')) {
            option = placeholder.querySelector('span').textContent.replace(':', '');

            search_input.value = placeholder.querySelector('span').nextSibling.textContent.replace(/^\s+/, '');
        }
        return option;
    }

    function setInputname() {
        switch (getContent()) {
            case 'titre':
                search_input.name = "titre";
                break;
            case 'auteur':
                search_input.name = "auteur";
                break;
            case 'sujet':
                search_input.name = "sujet";
                break;
            case 'depuis':
                search_input.name = "depuis";
                break;
            case 'de':
                search_input.name = "de";
                break;
            default:
                search_input.name = "key";
                break;
        }
    }

    // détecter l'entrée de mot clé de recherche comme titre:, auteur:, etc et les transformer en span
    function detectKeyword() {
        let options = ['titre:', 'auteur:', 'sujet:', 'depuis:'];
        let text = placeholder.textContent;

        // si le texte contient un des mots clés de options
        options.forEach(function (item) {
            if (text == item) {
                createSpanOption(text);
            }
        });
    }

    function createSpanOption(word) {
        let span = document.createElement('span');
        span.classList.add('search__editor-option');
        span.textContent = word;
        placeholder.innerHTML = '';
        placeholder.appendChild(span);

        setTimeout(() => {
            placeholder.innerHTML += '&nbsp;';
            setEndOfContenteditable(placeholder);
        }, 50);
    }
});

