<?php
/*
Plugin Name: Theme options
Plugin URI: http://dan-cole.com/
Description: This plugin adds options to your current theme and allows you to expand and customize the theme without modifying the theme files.
Author: Dan Cole
Version: 0.7
Author URI: http://dan-cole.com/
*/

/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 *	 A WordPress Plugin that allows backend addition, modification, and deletion of code.
 *	 Copyright (C) 2009	Dan Cole
 *
 *	 This program is free software: you can redistribute it and/or modify
 *	 it under the terms of the GNU General Public License as published by
 *	 the Free Software Foundation, either version 3 of the License, or
 *	 (at your option) any later version.
 *
 *	 This program is distributed in the hope that it will be useful,
 *	 but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 *	 GNU General Public License for more details.
 *
 *	 You should have received a copy of the GNU General Public License
 *	 along with this program.	If not, see <http://www.gnu.org/licenses/>.
 *
 */
if (!defined('THEME_OPTIONS_DIR')) {
	add_option('theme_options_dir', dirname(__FILE__) . '/');
	add_option('theme_options_url', WP_CONTENT_URL . '/plugins/theme-options/');
	add_option('theme_snippets_dir', dirname(__FILE__) . '/snippets/');
	add_option('theme_snippets_url', WP_CONTENT_URL . '/plugins/theme-options/snippets/');

	define( 'THEME_OPTIONS_DIR', get_option('theme_options_dir') );
	define( 'THEME_OPTIONS_URL', get_option('theme_options_url') );
	define( 'THEME_SNIPPETS_DIR', get_option('theme_snippets_dir') );
	define( 'THEME_SNIPPETS_URL', get_option('theme_snippets_url') );

	function theme_options_load_translation_file() {
		$plugin_path = plugin_basename(dirname( __FILE__ ) . '/translations');
		load_plugin_textdomain( 'theme_options', '', $plugin_path );
	}
	add_action('init', 'theme_options_load_translation_file');

	// Include files with useful code
	include_once THEME_OPTIONS_DIR . 'library/snippet_functions.php';
	include_once THEME_OPTIONS_DIR . 'library/panel_page.php';
	include_once THEME_OPTIONS_DIR . 'library/options_page.php';

	// Attach the 'Theme Options' Panel page
	add_action('admin_menu', 'attach_theme_options_panel_page'); //This adds a sub-page to the Appearance menu
	function attach_theme_options_panel_page() {
		add_theme_page('Theme Options Panel', 'Theme Options Panel', 8, theme_options_panel_page, theme_options_panel_page);
	}

	// Attach the Snippet Options Page
	add_action('admin_menu', 'attach_theme_options_snippets_page');
	function attach_theme_options_snippets_page() {
		add_theme_page('Snippet Options', 'Snippet Options', 8, theme_options_snippets_page, theme_options_snippets_page);
	}

	// Run Active Code Snippets
	$snippets = (array)get_option('active_snippets');
	for ($e=0; $e<count($snippets); $e++) {
		execute_snippet($snippets[$e]);
	}

	// Deal with POSTED data
	if (isset($_REQUEST['page'])) {
		$results = 'nothing';

		// Backend JavaScript
		if ( $_GET['page'] == 'theme_options_panel_page' || $_REQUEST['page'] == 'theme_options_snippets_page') {
			include_once THEME_OPTIONS_DIR . 'library/js/backend.php';
			add_action('admin_head', 'theme_options_codepress');
			add_action('admin_head', 'togglebox');
		}

		// Download a Code Snippet
		if (isset($_GET['page']) && $_GET['page'] == 'theme_options_panel_page' && isset($_GET['download'])) { 
			include_once THEME_OPTIONS_DIR . 'library/snippet_post.php';
			add_action('init', 'download_snippet');
		}
		// Process Theme Options Panel Form
		elseif (isset($_REQUEST['action']) && $_REQUEST['page'] == 'theme_options_panel_page') {
			include_once THEME_OPTIONS_DIR . 'library/snippet_post.php';
			add_action('init', 'theme_options_panel_post');
		}
		// Process Snippet Options Form
		elseif (isset($_REQUEST['action']) && $_REQUEST['page'] == 'theme_options_snippets_page') {
			include_once THEME_OPTIONS_DIR . 'library/options_post.php';
			add_action('init', 'theme_options_snippets_post');
		}
		do_action('theme_options_post');

		define('THEME_OPTIONS_POST_RESULTS', $results);
	}

	// Add CSS for Backend Styling
	include_once THEME_OPTIONS_DIR . 'library/css/backend.php';
	add_action('admin_head','theme_options_style');

	// Add CSS from snippets
	include_once THEME_OPTIONS_DIR . 'library/options_css.php';
	add_action('wp_head', 'theme_options_snippets_css');
}
/*
 * As I drown under a water fall of ideas, I yell for help toward those in the desert of self pity and hopelessness, but they only look back with eyes of jealousy.
 */
?>
