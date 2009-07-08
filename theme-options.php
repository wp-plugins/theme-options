<?php
/*
Plugin Name: Theme options
Plugin URI: http://dan-cole.com/
Description: This plugin adds options to your current theme and allows you to expand and customize the theme without modifying the theme files.
Author: Dan Cole
Version: 0.3
Author URI: http://dan-cole.com/
*/

/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 *   A WordPress Plugin that allows backend addition, modification, and deletion of code.
 *   Copyright (C) 2009  Dan Cole
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

define( 'THEME_OPTIONS_DIR', dirname(__FILE__) . '/' );
define( 'THEME_OPTIONS_URL', WP_CONTENT_URL . '/plugins/theme-options/' );
define( 'THEME_SNIPPETS_DIR', dirname(__FILE__) . '/snippets/' );
define( 'THEME_SNIPPETS_URL', WP_CONTENT_URL . '/plugins/theme-options/snippets/' );
define( 'THEME_HOOKS_DIR', dirname(__FILE__) . '/hooks/' );

// Hook Converting
include_once THEME_OPTIONS_DIR . 'library/hook_converter.php';

// Include files with useful code
include_once THEME_OPTIONS_DIR . 'library/snippet_functions.php';
include_once THEME_OPTIONS_DIR . 'library/panel_page.php';
include_once THEME_OPTIONS_DIR . 'library/options_page.php';

// Run Active Code Snippets
$snippets = (array)get_option('active_snippets');
for ($e=0; $e<count($snippets); $e++) {
	execute_snippet($snippets[$e]);
}

// Deal with POSTED data
if (isset($_REQUEST['page'])) {
  $results = 'nothing';

	// Backend JavaScript
	include_once THEME_OPTIONS_DIR . 'library/js/backend.php';
	add_action('admin_head', 'theme_options_codepress');
	add_action('admin_head', 'togglebox');

	// Download a Code Snippet
	if (isset($_GET['page']) && $_GET['page'] == 'theme_options_panel_page' && isset($_GET['download'])) { 
		include_once THEME_OPTIONS_DIR . 'library/snippet_post.php';
		add_action('init', 'download_snippet');
	}
	// Process Theme Options Panel Form
	elseif (isset($_REQUEST['action']) && $_REQUEST['page'] == 'theme_options_panel_page') {
		include_once THEME_OPTIONS_DIR . 'library/snippet_post.php';
		$results = theme_options_panel_post();
	}
	// Process Snippet Options Form
	elseif (isset($_REQUEST['action']) && $_REQUEST['page'] == 'theme_options_snippets_page') {
		include_once THEME_OPTIONS_DIR . 'library/options_post.php';
		$results = theme_options_snippets_post();
	}
	do_action('theme_options_post');

  define('THEME_OPTIONS_POST_RESULTS', $results);
}

// Attach the 'Theme Options' Panel page
add_action('admin_menu', 'attach_theme_options_panel_page'); //This adds a sub-page to the Appearance menu
function attach_theme_options_panel_page() {
  add_thickbox();
	add_theme_page('Theme Options Panel', 'Theme Options Panel', 8, theme_options_panel_page, theme_options_panel_page);
}

// Attach the Snippet Options Page
add_action('admin_menu', 'attach_theme_options_snippets_page');
function attach_theme_options_snippets_page() {
	add_theme_page('Snippet Options', 'Snippet Options', 8, theme_options_snippets_page, theme_options_snippets_page);
}

// Add CSS for Backend Styling
include_once THEME_OPTIONS_DIR . 'library/css/backend.php';
add_action('admin_head','theme_options_style');

// Add CSS from snippets
include_once THEME_OPTIONS_DIR . 'library/options_css.php';
add_action('wp_head', 'theme_options_snippets_css');

/*
 * As I drown under a water fall of ideas, I yell for help toward those in the desert of self pity and hopelessness, but they only look back with eyes of jealousy.
 */
?>
