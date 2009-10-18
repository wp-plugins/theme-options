<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

define( 'THEME_OPTIONS_DIR', get_option('theme_options_dir') );
include_once THEME_OPTIONS_DIR . 'library/snippet_functions.php';

$table_data = fetch_snippets();
$snippets_data = $table_data['snippets'];

for ($s = 0; $s < count($snippet_data); $s++) {
	delete_option('snippet_' . $snippets_data[$s]['name'] . '_code');
	delete_option('snippet_' . $snippets_data[$s]['name'] . '_status');
	delete_option('snippet_' . $snippets_data[$s]['name'] . '_switch');
}

delete_option('database_snippets');
delete_option('active_snippets');

?>
