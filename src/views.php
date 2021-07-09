<?php

/**
 * Views represent pages, and fill and render templates.
 * To add a view, create a function that calls render()
 * or redirects to another view and add it to the urls array in urls.php
 */

require_once "sturdy/template.php";

use function Sturdy\render;

require_once "models.php";

function landingPage(){

	$context = [
		"title" => "Hello world!",
	];
	render("landingpage.php", $context);
}

function _404(){

	http_response_code(404);
	$context = [
		"title" => "Not found.",
	];
	render("404.php", $context);
}
