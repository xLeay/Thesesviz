<?php

// global vars
$basepath = ""; // ex: "/app"
$request = $_SERVER['REQUEST_URI'];
$request = explode('?', $request)[0];
$request = str_replace($basepath, '', $request);

define('ROOT', __DIR__);

// // PDO
// $servername = "localhost";
// $username = "root";
// $password = "";
// $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4', PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8mb4");

// try {
//     $conn = new PDO("mysql:host=$servername;dbname=theseviz", $username, $password, $options);

//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
// }

// load all files in functions folder
$functions = glob(dirname(__FILE__) . '/includes/functions/*.php');

foreach ($functions as $function) {
    require_once $function;
}

// generate canonical link of the requested page

const CANONICAL_QUERY_STRINGS = ['seed'];

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'] . $request;
$queryStrings = explode('&', $_SERVER['QUERY_STRING']);
$canonicalQueryStrings = [];

foreach ($queryStrings as $key => $queryString) {
    $queryString = explode('=', $queryString);
    if (in_array($queryString[0], CANONICAL_QUERY_STRINGS)) {
        $canonicalQueryStrings[] = $queryString[0] . '=' . $queryString[1];
    }
}

$canonical = $url . "?hl=fr" . (sizeof($canonicalQueryStrings) > 0 ? "&" . implode('&', $canonicalQueryStrings) : '');

function loadAsset($page, $type)
{
    global $basepath;
    if ($type == 'css' && file_exists("src/styles/pages/" . $page . ".css")) {
        return "<link rel=\"stylesheet\" href=\"$basepath/src/styles/pages/" . $page . ".css?v=" . md5_file("src/styles/pages/" . $page . ".css") . "\">";
    }
    if ($type == 'js' && file_exists("src/scripts/pages/" . $page . ".js")) {
        return "<script src=\"$basepath/src/scripts/pages/" . $page . ".js?v=" . md5_file("src/scripts/pages/" . $page . ".js") . "\" defer></script>";
    }
    return "";
}

function loadPage($page, $with_head = true)
{

    include_once "includes/classes/search.class.php";

    global $basepath, $og, $canonical;
    $path =  "pages" . DIRECTORY_SEPARATOR . $page . ".php";
    $headPath = "pages" . DIRECTORY_SEPARATOR . $page . ".head.php";
    if (file_exists($path)) {
        if ($with_head) {
            $appendHead = loadAsset($page, 'css');
            $externalHeadFile = file_exists($headPath) ? $headPath : false;
            include "includes/head.php";
            require "includes/components/nav.php";
        }
        include($path);
        if ($with_head) {
            $appendBody = loadAsset($page, 'js');
            include "includes/endbody.php";
        }
    }
}

switch ($request) {
    case "/":
        $og = (object) [
            "title" => "Thesesviz - Acceuil",
            "description" => "Thesesviz - Acceuil"
        ];
        loadPage("home");
        break;
    case "/q": // query (search)
        $og = (object) [
            "title" => "Recherche de thèse",
            "description" => "Recherchez les thèses de doctorat en France"
        ];
        loadPage("search");
        break;
    case "/import":
        $og = (object) [
            "title" => "Import des thèses",
            "description" => "Import des thèses"
        ];
        loadPage("import");
        break;
    case "/ajax/search":
        include "includes/ajax/search.php";
        break;
    case "/404":
        $og = (object) [
            "title" => "",
            "description" => ""
        ];
        loadPage("404");
        break;
    default:
        http_response_code(404);
        loadPage("404");
        return false;
}
