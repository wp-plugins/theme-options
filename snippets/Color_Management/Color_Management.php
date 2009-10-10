<?php
/*
name: Color Management
author: Dan Cole
url: http://dan-cole.com/
description: Manage theme colors though a graphical backend. 
tags: color, theme, management
*/

/*
 * @author Dan Cole
 * @copyright 2009
 * @website http://dan-cole.com
 * @package 'Color Management' snippet for 'Theme Options', a WordPress plugin
 *
 */

function color_management_page() {
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];
	color_table();
?>
	<hr />
<?php
if (($action == "edit") || isset($fail)) {
	for ($e=0; $e<count($color_data[1]); $e++) {
		if ($color_data[1][$e] == $_GET['name'] ) {
			$color = $color_data[0][$e];
			$name = $color_data[1][$e];
			$author = $color_data[2][$e];
			$tags = implode(", ", $color_data[3][$e]);
		}
	}
?>
	<h3><?php _e('Edit Color', 'theme-options'); ?></h3>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row"><label for="editcolor"><?php _e('Color', 'theme-options'); ?></label></th>
			<td><input type="text" class="medium-text" value="<?php echo (isset($fail)) ? $_POST['editcolor'] : $color; ?>" name="editcolor"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="editname"><?php _e('Name', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php echo (isset($fail)) ? $_POST['editname'] : $name; ?>" name="editname"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="edittags"><?php _e('Tags', 'theme-options'); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php echo (isset($fail)) ? $_POST['edittags'] : $tags; ?>" name="edittags"/>
				<span class="setting-description"><?php _e('Seperate tags with a comma.', 'theme-options'); ?></span>
			</td>
		</tr>
		</tbody>
	</table>
	<p class="submit">
	<input type="hidden" name="oldname" value="<?php echo (isset($fail)) ? $_POST['oldname'] : $name; ?>" />
	<input type="hidden" name="editauthor" value="<?php global $current_user; get_currentuserinfo(); echo $current_user->display_name; ?>" />
	<input type="hidden" name="action" value="save_edit" />
	<input type="submit" name="Submit" value="<?php _e('Edit Color', 'theme-options') ?>" />
	</p>
<?php
}
else {
?>
	<h3><?php _e('Add Color', 'theme-options'); ?></h3>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row"><label for="newcolor"><?php _e('Color', 'theme-options'); ?></label></th>
			<td><input type="text" class="medium-text" value="<?php if (!isset($add_group_error)) echo $_POST['newcolor']; ?>" name="newcolor"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="newname"><?php _e('Name', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php if (!isset($add_group_error)) echo $_POST['newname']; ?>" name="newname"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="newtags"><?php _e('Tags', 'theme-options'); ?></label></th>
			<td>
				<input type="text" class="regular-text" value="<?php if (!isset($add_group_error)) echo $_POST['newtags']; ?>" name="newtags"/>
				<span class="setting-description"><?php _e('Seperate tags with a comma.', 'theme-options'); ?></span>
			</td>
		</tr>
		</tbody>
	</table>
	<p class="submit">
	<input type="hidden" name="newauthor" value="<?php global $current_user; get_currentuserinfo(); echo $current_user->display_name; ?>" />
	<input type="hidden" name="action" value="add" />
	<input type="submit" name="Submit" value="<?php _e('Add Color', 'theme-options') ?>" />
	</p>
<?php
} // End Else statement, add color
?>	
	</form>
	</div>
	<?php 
} // End function color_management_page

function attach_color_management_snippet_page() {
	add_theme_page('Color Management', 'Color Management', 8, color_management_page, color_management_page);
}
add_action('admin_menu', 'attach_color_management_snippet_page');


function color_management_post() {
	$result = 'nothing';
	if (isset($_GET['page']) && $_GET['page'] == 'color_management_page' && isset($_REQUEST['action'])) { 
		global $wpdb;
		$table_name = $wpdb->prefix . "theme_options_colors";

		$filter_author = 0;
		$filter_tag = 0;
		if ($_REQUEST["filter"]) {
			$action = 'filter';
			$filter_author = $_REQUEST["author"];
			$filter_tag = $_REQUEST["tag"];
		}
		$action = '';
		foreach( array('delete-selected') as $action_key ) {
			if( isset($_POST[$action_key]) ) {
				$action = $action_key;
				break;
			}
		}

		if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];
		$color = isset($_REQUEST['color']) ? $_REQUEST['color'] : '';

		if( !empty($action) ) {
			update_option('theme_last_modified', date('ymdHi'));
			switch( $action ) {
				case 'add':
					if ( (!empty($_POST['newname'])) && (!empty($_POST['newcolor'])) ) {
						$newtags = (array)explode(",", $_POST['newtags']);
						foreach ($newtags as $newtag) $newtag = trim($newtag);
						add_color("theme_options_colors", trim($_POST['newcolor']), trim($_POST['newname']), trim($_POST['newauthor']), $newtags);
						$result = 'added';
					}
					break;
				case 'save_edit':
					if ( (!empty($_POST['editcolor'])) && (!empty($_POST['editname'])) ) {
						$edittags = (array)explode(",", $_POST['edittags']);
						foreach ($edittags as $edittag) $edittag = trim($edittag, " ");
						$dan = edit_color("theme_options_colors", trim($_POST['editcolor']), trim($_POST['editname']), trim($_POST['editauthor']), $edittags, $_POST['oldname']);
					}
					else $result = FALSE;
					break;
				case 'delete':
					$result = delete_color($table_name, $color);
					break;
				case 'delete-selected':
					$outputs = array();
					foreach ( (array)$_POST['checked'] as $color) {
						$outputs[] = delete_color($table_name, $color);
					}
					if (in_array('persmission_denied', $outputs)) {
						$result = 'persmission_denied';
					}
					break;
			}
		}
	}
	define('THEME_OPTIONS_POST_RESULTS', $result);
}
add_action('theme_options_post', 'color_management_post');

function color_table($type = 'normal') {
	global $wpdb;
	$table_name = $wpdb->prefix . "theme_options_colors";

	$filter_size = 0;
	$filter_tag = 0;
	if ($_REQUEST["filter"]) {
		$action = 'filter';
		$filter_author = $_REQUEST["author"];
		$filter_tag = $_REQUEST["tag"];
	}

	$color_data = fetch_color_mgmt_colors($filter_author, $filter_tag, $table_name);
?>
	<div class='wrap'>
	<form name='color' method='post' action='themes.php?page=color_management_page' enctype='multipart/form-data'>
<?php	wp_nonce_field('update-options'); ?>
	<h2><?php _e('Colors', 'theme-options'); ?></h2>
<?php
	if (THEME_OPTIONS_POST_RESULTS == 'false') {
		echo "<div class='error'><p>"; _e('There was a problem while process the information you submitted.'); echo "</p></div>";
	}
	elseif (THEME_OPTIONS_POST_RESULTS == 'persmission_denied') {
		echo "<div class='error'><p>"; _e('You did <b>not</b> have the correct server permissions to complete the task.'); echo "</p></div>";
	}
?>
	<div class='tablenav'>
		<div class='alignleft'>
			<input type="submit" class="button-secondary delete" name="delete-selected" value="Delete"/>
			<select name="author">
				<option value="0" selected="selected"><?php _e('Show all Authors', 'theme-option'); ?></option>
				<?php
				for ($s=0; $s<count($color_data[4]); $s++) {
					echo "<option value='" . $color_data[4][$s] . "'>" . $color_data[4][$s] . "</option>";
				}
				?>
			</select>
			<select name="tag">
				<option value="0" selected="selected"><?php _e('Show all Tags', 'theme-options'); ?></option>
				<?php
				for ($s=0; $s<count($color_data[5]); $s++) {
					echo "<option value='" . $color_data[5][$s] . "'>" . $color_data[5][$s] . "</option>";
				}
				?>
			</select>
			<input type="submit" class="button-secondary" name="filter" value="Filter" id="color-query-submit"/>
		</div>
		<br class='clear'>
	</div>
	<br class='clear'>
	<table class='widefat'>
		<thead>
			<tr>
				<th class='check-column'><input type='checkbox'></th>
				<th><?php _e('Color', 'theme-options'); ?></th>
				<th><?php _e('Name', 'theme-options'); ?></th>
				<th><?php _e('Author', 'theme-options'); ?></th>
				<th><?php _e('Tags', 'theme-options'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class='check-column'><input type='checkbox'></th>
				<th><?php _e('Color', 'theme-options'); ?></th>
				<th><?php _e('Name', 'theme-options'); ?></th>
				<th><?php _e('Author', 'theme-options'); ?></th>
				<th><?php _e('Tags', 'theme-options'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			if ($type == 'inserter') {
				echo "<tr class='"; 
					switch ($r%2) { case 1: echo "alternate"; break; case 2: echo ""; break; }
				echo "'>";
				echo "<th class='manage-column column-cb check-column'>&nbsp;</th>";
				echo "<td><span title='Inherit' >Inherit</span><br style='margin: 15px;'/>";
				echo "<span class='inline' title='Inherit' ><a title='Select this color.' href='" . $_GET['url'] . "/wp-content/plugins/theme-options/snippets/Color_Management.php?item=" . $_GET['item'] . "&amp;color=transparent&amp;tab=color&amp;url=" . $_GET['url'] . "'>Select</a></span>";
				echo "</td>";
				echo "<td><strong>" . __('Inherit', 'theme-options') . "</strong></td>";
				echo "<td>" . __('Default', 'theme-options') . "</td>";
				echo "<td>&nbsp;</td>";
				echo "</tr>";
			}
			for ($r=0; $r<count($color_data[0]); $r++) {
				echo "<tr class='"; 
					switch ($r%2) { case 1: echo "alternate"; break; case 2: echo ""; break; }
				echo "'>";
				echo "<th class='manage-column column-cb check-column'><input type='checkbox' value='" . $color_data[1][$r] . "' name='checked[]'></th>";
				echo "<td><span title='" . $color_data[0][$r] . "' class='color_box' style='padding: 0px 30px 10px; background-color: " . $color_data[0][$r] . ";'>&nbsp;</span><br style='margin: 15px;'/>";
				if ($type == 'inserter') {
					echo "<span class='inline' title='" . $color_data[0][$r] . "' ><a title='Select this color.' href='" . $_GET['url'] . "/wp-content/plugins/theme-options/snippets/Color_Management.php?item=" . $_GET['item'] . "&amp;color=" . $color_data[0][$r] . "&amp;tab=color&amp;url=" . $_GET['url'] . "'>Select</a> | </span>";
				}
				else {
					echo "<span class='inline'><a title='Edit this color' href='themes.php?page=color_management_page&action=edit&name=" . $color_data[1][$r] . "'>Edit</a> | </span>";
				}
				echo "<span class='inline'><a title='View this color' href='themes.php?page=color_management_page&action=edit&name=" . $color_data[1][$r] . "'>View</a></span>";
				echo "</td>";
				echo "<td><strong>" . $color_data[1][$r] . "</strong></td>";
				echo "<td>" . $color_data[2][$r] . "</td>";
				echo "<td>";
				echo implode(", ", $color_data[3][$r]);
				echo "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
<?php
}

function create_color_table($table_name) {
	global $wpdb;
	if (!$table_name) {
		$table_name = $wpdb->prefix . "theme_options_colors";
	}
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
		color text NOT NULL,
		name text NOT NULL, 
		author text NOT NULL, 
		tags text NOT NULL
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //Get dbDelta function to insert sql data
		dbDelta($sql); //Insert sql data through special WordPress function
	}
	else {
		return 'permission_denied';
	}
}

function fetch_color_mgmt_colors($author = 0, $filter_tag = 0, $table_name) {
	global $wpdb;
	if (!$table_name) {
		$table_name = $wpdb->prefix . "theme_options_colors";
	}
	create_color_table($table_name);
	// Get Data and filter out all but a single Author if requested
	if ($name) {
		$colors = $wpdb->get_col("SELECT color FROM " . $table_name . " WHERE name='" . $name . "'");
		$names = $wpdb->get_col("SELECT name FROM " . $table_name . " WHERE name='" . $name . "'");
		$authors = $wpdb->get_col("SELECT author FROM " . $table_name . " WHERE name='" . $name . "'");
		$pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name . " WHERE name='" . $name . "'");
	}
	else {
		$colors = $wpdb->get_col("SELECT color FROM " . $table_name);
		$names = $wpdb->get_col("SELECT name FROM " . $table_name);
		$authors = $wpdb->get_col("SELECT author FROM " . $table_name);
		$pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name);
	}

	$all_authors = $wpdb->get_col("SELECT author FROM " . $table_name);
	$all_pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name);

	// Get list of all Authors and Tags
	$author_list = array();
	foreach ((array)$all_authors as $author) {
		if (!in_array($author, $author_list)) $author_list[] = $author;
	}
	$tag_list = array();
	foreach ((array)$all_pre_tags as $tags_list) {
		$temp = unserialize($tags_list);
		foreach ((array)$temp as $tag) {
			if (!in_array($tag, $tag_list)) $tag_list[] = $tag;
		}
	}
	$tags = array();
	foreach ((array)$pre_tags as $tags_list) {
		$tags[] = unserialize($tags_list);
	}
	// Filter out all but a single tag if requested
	if ($filter_tag != "0") {
		$fetch = array( array(), array(), array(), array(), $author_list, $tag_list );
		for ($f=0; $f<count($names); $f++) {
			if (in_array($filter_tag, $tags[$f])) {
				$fetch[0][] = $colors[$f];
				$fetch[1][] = $names[$f];
				$fetch[2][] = $authors[$f];
				$fetch[3][] = $tags[$f];
			}
		}
	}
	else {
		// Put data together
		$fetch = array();
		$fetch[] = $colors;
		$fetch[] = $names;
		$fetch[] = $authors;
		$fetch[] = $tags;
		$fetch[] = $author_list;
		$fetch[] = $tag_list;
	}
	return $fetch;
}

function add_color($table_name, $color, $name, $author, $tags) {
	global $wpdb;
	create_color_table($table_name);
	$insert = "INSERT INTO " . $table_name .
		" (color, name, author, tags) " .
		"VALUES ('" . $wpdb->escape($color) . "', '" . $wpdb->escape($name) . "', '" . $wpdb->escape($author) . "', '" . $wpdb->escape(serialize($tags)) . "')";
	return $wpdb->query( $insert );
}

function delete_color($table_name, $name) {
	global $wpdb;
	$insert = "DELETE FROM " . $table_name . " WHERE name='" . $name . "'";
	$result = $wpdb->query( $insert );
	return $result;
}

function edit_color($table_name, $color, $name, $author, $tags, $old_name) {
	global $wpdb;
	$insert = "UPDATE " . $table_name . " SET ";
	$insert .= "color='" . $color . "'";
	$insert .= ", name='" . $name . "'";
	$insert .= ", author='" . $author . "'";
	$insert .= ", tags='" . serialize($tags) . "'";
	$insert .= " WHERE name='" . $old_name . "'";
	return $wpdb->query( $insert );
}

function color_management_inserter($color_name) {
	$background = 'url(' . THEME_SNIPPETS_URL . 'Color_Management/color.png)';
	if (get_option($color_name)) {
		$background = get_option($color_name);
	}
	echo '<a title="Select a Color" class="thickbox" style="text-decoration: none;" href="' . THEME_SNIPPETS_URL . 'Color_Management/inserter.php?item=' . $color_name . '&amp;tab=color&amp;url=' . get_bloginfo('url') . '"><span class="color_box" style="padding: 4px 10px; border: 1px solid #DDD; -moz-border-radius: 5px; background: ' . $background . '">&nbsp;</span></a>';
}
add_action('color_input', 'color_management_inserter');

?>
