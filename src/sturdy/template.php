<?php

/**
 * Functions for interacting with templates
 */

namespace Sturdy;

/**
 * Render template extending base template, passing variables through scope. For use in views.
 * 
 * @param string $template File name of the template (in templates/)
 * @param array $context Variables to be passed to the template
 */
function render($template, $context = [], $extends = DEFAULT_BASE_TEMPLATE){

	if(!$extends) $extends = $template;
	include "../templates/".$extends;
}

/**
 * Render template passing variables, into a string.
 * 
 * Useful for filling mail templates.
 * 
 * @param string $template File name of the template (in templates/)
 * @param array $context Variables to be passed to the template
 */
function renderToString($template, $context = []){

	ob_start();
	include "../templates/".$template;
	$result = ob_get_contents();
	ob_end_clean();
	return $result;
}
