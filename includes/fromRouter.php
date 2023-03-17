<?php
$from_router = ($_SERVER['PHP_SELF'] == "/router.php") ? true : false;

if (!$from_router) {
    header('Location: /');
}
?>