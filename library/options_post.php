<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */
function theme_options_snippets_post() {
	if (! wp_verify_nonce($_REQUEST['_wpnonce'], 'theme_options') ) die('Security check');

	$task = 'passed';
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];

	if ( in_array($_REQUEST['action_key'], array('activate-selected', 'deactivate-selected', 'delete-selected')) ) {
		$action = $_REQUEST['action_key'];
	}

	if( !empty($action) ) {
		switch( $action ) {
			case 'save':
				$other_fields = array('action', 'Submit');
				$other_fields = apply_filters('options_post_other_fields', $other_fields);
				foreach ($_POST as $key => $value) {
					if (!in_array($key, $other_fields)) {
						update_option($key, $value);
					}
				}
				break;
		}
	}
	return $task;
}
/*
 * A cell does its task not for its self, but for me. So I do my task not for myself, but for the greater good.
 */
?>
