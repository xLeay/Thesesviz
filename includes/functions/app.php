<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function debug(...$var)
{
    foreach ($var as $value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function createCookie($id, $pseudo, $mdp)
{
    setcookie('auth', $id . '--' . sha1($pseudo . $mdp . $_SERVER['REMOTE_ADDR']), time() + 3600 * 24 * 3, '/', 'thesesviz.test', false, true);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function unsetCookie()
{
    setcookie('auth', '', time() - 3600, '/', 'thesesviz.test', false, true);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction pour générer un identicon par défaut quand l'utilisateur se connecte et n'a pas de photo de profil
function generateIdenticon($mail, $size = 32)
{
    $emailHash = md5(strtolower(trim($mail)));
    $gravatarUrl = "https://www.gravatar.com/avatar/$emailHash?d=identicon&s=$size";
    return $gravatarUrl;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction pour augmenter la taille de l'identicon. ex: pour la page profil
function increaseIdenticonSize($mail, $size = 50)
{
    // ancien mail : "https://www.gravatar.com/avatar/213be6e5286eef2a656584e8e2af4da8?d=identicon&s=30"
    // nouveau mail : "https://www.gravatar.com/avatar/213be6e5286eef2a656584e8e2af4da8?d=identicon&s=50"
    $mail = str_replace("s=32", "s=$size", $mail);
    $gravatarUrl = "$mail";
    return $gravatarUrl;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction pour récupérer l'URL de l'image Open Graph d'une page web
function getOgImageUrl($pageUrl) {
    // Activer la gestion des erreurs internes de libxml
    libxml_use_internal_errors(true);

    // Récupérer le contenu de la page web
    $pageContent = file_get_contents($pageUrl);

    // Créer un objet DomDocument et charger le contenu HTML
    $domDocument = new DomDocument();
    $domDocument->loadHTML($pageContent);

    // Créer un objet DomXPath pour effectuer une recherche XPath
    $xpath = new DomXPath($domDocument);

    // Rechercher l'élément meta qui correspond à l'image Open Graph
    $ogImageElement = $xpath->query("//meta[@property='og:image']")->item(0);

    // Récupérer l'URL de l'image s'il existe
    if ($ogImageElement !== null && $ogImageElement instanceof DOMElement) {
        $ogImageUrl = $ogImageElement->getAttribute("content");
        return $ogImageUrl;
    }
}