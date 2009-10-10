<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */
function theme_options_panel_post() {
	if (! wp_verify_nonce($_REQUEST['_wpnonce'], 'theme_options') ) die('Security check');

	$task = 'passed';
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];
	$snippet = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

	if ( in_array($_REQUEST['action_key'], array('activate-selected', 'deactivate-selected', 'export-selected', 'delete-selected')) ) {
		$action = $_REQUEST['action_key'];
	}
	if( !empty($action) ) {
		switch( $action ) {
			case 'add':
				$task = add_snippet($_POST['code']);
				break;
			case 'activate':
				activate_snippet($snippet);
				break;
			case 'activate-selected':
				foreach ( (array)$_POST['checked'] as $snippet) {
					activate_snippet($snippet);
				}
				break;
			case 'deactivate':
				deactivate_snippet($snippet);
				break;
			case 'deactivate-selected':
				foreach ( (array)$_POST['checked'] as $snippet) {
					deactivate_snippet($snippet);
				}
				break;
			case 'export-selected':
				include_once THEME_OPTIONS_DIR . 'library/export.php';
				add_action('init', 'export_snippets');
				break;
			case 'delete-selected':
				$results = array();
				foreach ( (array)$_POST['checked'] as $snippet) {
					$results[] = delete_snippet($snippet);
				}
				if (in_array(FALSE, $results)) $task = 'persmission_denied';
				break;
			case 'copy':
				copy_snippet($_GET['name']);
				break;
			case 'import':
				$file_path = $_FILES['snippet_file']['tmp_name'];
				if ($_FILES['snippet_file']['type'] == 'text/xml') {
					include_once THEME_OPTIONS_DIR . 'library/export.php';
					import_snippets($file_path);
				}
				else {
					echo "<h1>WRONG TYPE!</h1>";
					$fp = fopen($file_path, 'r');
					$code = fread($fp, filesize($file_path));
					fclose($fp);
					add_snippet($code);
				}
				break;
			case 'modify':
				$task = modify_snippet($_POST['name'], $_POST['code']);
				break;
			case 'save':
				$other_fields = array('action', 'Submit');
				$other_fields = apply_filters('snippet_post_other_fields', $other_fields);
				foreach ($_POST as $key => $value) {
					if (!in_array($key, $other_fields)) {
						update_option($key, $value);
					}
				}
				break;
		}
	}
	//wp_redirect(get_option('siteurl') . '/wp-admin/themes.php?page=theme_options_panel_page');
	return $task;
}
/*
 * A cell does its task not for its self, but for me. So I do my task not for myself, but for the greater good.
*/
?>
