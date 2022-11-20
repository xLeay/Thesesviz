
document.addEventListener('DOMContentLoaded', function () {

    const search_thesis = document.querySelector('.search_thesis');
    const search_icon = document.querySelector('.search_icon');
    const search_icon_parent = document.querySelector('.search__icon');
    const search_options = document.querySelector('.search_options');
    const search_option_item = document.querySelectorAll('.search_options__item');
    const search_input = document.getElementById('search_input');


    const primary = getComputedStyle(document.body).getPropertyValue('--primary');
    const secondary = getComputedStyle(document.body).getPropertyValue('--secondary');
    const placeholder = document.querySelector('.search__editor-placeholder-inner');

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

            
            search_thesis.submit();
        }
    });

    // On affiche les options de recherche lorsqu'on clique sur le formulaire de recherche
    document.addEventListener('click', function (e) {
        if (e.target == search_thesis || search_thesis.contains(e.target) && e.target != search_options && search_options.contains(e.target) == false) {

            search_options.style.display = 'flex';
            placeholder.focus();

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
            // console.log(nodes);
            if (nodes.length == 1) {
                item.remove();
                showPlaceholder();
            }
        
        });
    }

    // fonction qui retour le résultat de la recherche car une div en contenteditable ne peut pas être utilisé dans un formulaire
    function getContent() {
        search_input.value = placeholder.textContent;
        console.log(search_input.value, placeholder.textContent);
    }

    // détecter l'entrée de mot clé de recherche comme titre:, auteur:, etc et les transformer en span
    function detectKeyword() {
        let options = ['titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'];
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