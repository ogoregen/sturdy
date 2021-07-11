<?php

//calls correct view depending on url

require_once __DIR__."/../urls.php";
require_once __DIR__."/../views.php";

session_set_cookie_params(2147483647); //maximum cookie lifespan
session_start();

$uri = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL);

if($p = strpos($uri, '?')){

    $uri = substr($uri, 0, $p);
}

$uri = substr($uri, 1, strlen($uri));

if(array_key_exists($uri, $urls)) $view = $urls[$uri]; //if url is valid
else $view = $urls["404"];

$view();
