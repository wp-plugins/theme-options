<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */

function theme_options_snippets_css() {
	echo "<style>";
	do_action('theme_options_snippets_css');
	echo "</style>";
}

?>
