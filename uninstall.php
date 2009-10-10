<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

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
