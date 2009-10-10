<?php
/*
name: Image Management
author: Dan Cole
url: http://dan-cole.com/
description: Manage theme images though a graphical backend. 
tags: image, theme, management
*/

/*
 * @author Dan Cole
 * @copyright 2009
 * @website http://dan-cole.com
 * @package 'Image Management' snippet for 'Theme Options', a WordPress plugin
 *
 */

define( 'IMAGE_MGMT_IMAGE_URL', WP_CONTENT_URL . '/plugins/theme-options/images/' );
define( 'IMAGE_MGMT_IMAGE_DIR', dirname(dirname(__FILE__)) . '/images/' );

function image_management_page() {
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];
	image_table();
?>
	<hr />
<?php
if ( ($action == "edit") || isset($fail) ) {
	for ($e=0; $e<count($image_data[1]); $e++) {
		if ($image_data[1][$e] == $_GET['image'] ) {
			$name = $image_data[0][$e];
			$url = $image_data[1][$e];
			$size = $image_data[2][$e];
			$description = $image_data[3][$e];
			$tags = implode(", ", $image_data[4][$e]);
		}
	}
?>
	<h3><?php _e('Edit image Group', 'theme-options'); ?></h3>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row"><label for="editname"><?php _e('Image Name', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php echo (isset($fail)) ? $_POST['editname'] : $name; ?>" name="editname"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="editdescription"><?php _e('Description', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php echo (isset($fail)) ? $_POST['editdescription'] : $description; ?>" name="editdescription"/></td>
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
	<input type="hidden" name="editurl" value="<?php echo (isset($fail)) ? $_POST['editurl'] : $url; ?>" />
	<input type="hidden" name="action" value="save_edit" />
	<input type="submit" name="Submit" value="<?php _e('Edit image Group', 'theme-options') ?>" />
	</p>
<?php
}
else {
?>
	<h3><?php _e('Add Image'); ?></h3>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<th scope="row"><label for="image_file"><?php _e('Image File', 'theme-options'); ?></label></th>
			<td><input type="hidden" value="2097152" name="max_file_size"/><input name="image_file" type="file" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="newname"><?php _e('Image Name', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php if (!isset($add_group_error)) echo $_POST['newname']; ?>" name="newname"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="newdescription"><?php _e('Description', 'theme-options'); ?></label></th>
			<td><input type="text" class="regular-text" value="<?php if (!isset($add_group_error)) echo $_POST['newdescription']; ?>" name="newdescription"/></td>
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
	<input type="hidden" name="action" value="add" />
	<input type="submit" name="Submit" value="<?php _e('Upload Image', 'theme-options'); ?>" />
	</p>
<?php
} // End Else statement, add image
?>	
	</form>
	</div>
	<?php 
} // End function image_management_page

function attach_image_management_snippet_page() {
	add_theme_page('Image Management', 'Image Management', 8, image_management_page, image_management_page);
}
add_action('admin_menu', 'attach_image_management_snippet_page');


function image_management_post() {
	$result = 'nothing';
	if (isset($_GET['page']) && $_GET['page'] == 'image_management_page' && isset($_REQUEST['action'])) { 
		$table_name = "theme_options_images";

		$filter_size = 0;
		$filter_tag = 0;
		if ($_REQUEST["filter"]) {
			$action = 'filter';
			$filter_size = $_REQUEST["size"];
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
		$image = isset($_REQUEST['image']) ? $_REQUEST['image'] : '';

		if( !empty($action) ) {
			update_option('theme_last_modified', date('ymdHi'));
			switch( $action ) {
				case 'add':
					if ( (!empty($_POST['newname'])) && (!empty($_POST['newdescription'])) && (!empty($_POST['newtags'])) ) {
						if(move_uploaded_file($_FILES['image_file']['tmp_name'], IMAGE_MGMT_IMAGE_DIR . $_FILES["image_file"]["name"])) {
							$url = $_FILES['image_file']['name'];
							list($width, $height, $type, $attr) = getimagesize(IMAGE_MGMT_IMAGE_DIR . $url);
							$size = $width . "x" . $height;
							$newtags = (array)explode(",", $_POST['newtags']);
							foreach ($newtags as $newtag) $newtag = trim($newtag, " ");
							add_images("theme_options_images", trim($_POST['newname']), $url, $size, trim($_POST['newdescription']), $newtags);
							$result = 'uploaded';
						}
						else {
							define('THEME_OPTIONS_POST_RESULTS', 'persmission_denied');
						}
					}
					break;
				case 'save_edit':
					if ( (!empty($_POST['editname'])) && (!empty($_POST['editurl'])) && (!empty($_POST['editdescription'])) && (!empty($_POST['edittags'])) ) {
						$size = 0; // In the future you should be able to scale the image
						$edittags = (array)explode(",", $_POST['edittags']);
						foreach ($edittags as $edittag) $edittag = trim($edittag, " ");
						$dan = edit_images("theme_options_images", trim($_POST['editname']), $_POST['editurl'], $size, trim($_POST['editdescription']), $edittags);
					}
					else $result = FALSE;
					break;
				case 'delete':
					$result = delete_images($table_name, $image);
					break;
				case 'delete-selected':
					$outputs = array();
					foreach ( (array)$_POST['checked'] as $image) {
						$outputs[] = delete_images($table_name, $image);
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
add_action('theme_options_post', 'image_management_post');

function image_table($type = 'normal') {
	global $wpdb;
	$table_name = $wpdb->prefix . "theme_options_images";

	$filter_size = 0;
	$filter_tag = 0;
	if ($_REQUEST["filter"]) {
		$action = 'filter';
		$filter_size = $_REQUEST["size"];
		$filter_tag = $_REQUEST["tag"];
	}

	$image_data = fetch_image_mgmt_images($filter_size, $filter_tag, $table_name);
?>
	<div class='wrap'>
	<form name='image' method='post' action='themes.php?page=image_management_page' enctype='multipart/form-data'>
<?php	wp_nonce_field('update-options'); ?>
	<h2><?php _e('Images'); ?></h2>
<?php
	if (THEME_OPTIONS_POST_RESULTS == 'false') {
		echo "<div class='error'><p>"; _e('There was a problem while process the information you submitted.'); echo "</p></div>";
	}
	elseif (THEME_OPTIONS_POST_RESULTS == 'persmission_denied') {
		echo "<div class='error'><p>"; _e('You did <b>not</b> have the correct server permissions to complete the task.'); echo "</p></div>";
	}
	elseif (THEME_OPTIONS_POST_RESULTS == 'uploaded') {
		echo "<div class='updated'><p>"; _e('Your image was <b>successfully</b> uploaded.'); echo "</p></div>";
	}
?>
	<div class='tablenav'>
		<div class='alignleft'>
			<input type="submit" class="button-secondary delete" name="delete-selected" value="Delete"/>
			<select name="size">
				<option value="0" selected="selected"><?php _e('Show all Sizes'); ?></option>
				<?php
				for ($s=0; $s<count($image_data[5]); $s++) {
					echo "<option value='" . $image_data[5][$s] . "'>" . $image_data[5][$s] . "</option>";
				}
				?>
			</select>
			<select name="tag">
				<option value="0" selected="selected"><?php _e('Show all Tags', 'theme-options'); ?></option>
				<?php
				for ($s=0; $s<count($image_data[6]); $s++) {
					echo "<option value='" . $image_data[6][$s] . "'>" . $image_data[6][$s] . "</option>";
				}
				?>
			</select>
			<input type="submit" class="button-secondary" name="filter" value="Filter" id="image-query-submit"/>
		</div>
		<br class='clear'>
	</div>
	<br class='clear'>
	<table class='widefat'>
		<thead>
			<tr>
				<th class='check-column'><input type='checkbox'></th>
				<th><?php _e('Image', 'theme-options'); ?></th>
				<th><?php _e('Name', 'theme-options'); ?></th>
				<th><?php _e('Size (px)', 'theme-options'); ?></th>
				<th><?php _e('Description', 'theme-options'); ?></th>
				<th><?php _e('Tags', 'theme-options'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class='check-column'><input type='checkbox'></th>
				<th><?php _e('Image', 'theme-options'); ?></th>
				<th><?php _e('Name', 'theme-options'); ?></th>
				<th><?php _e('Size (px)', 'theme-options'); ?></th>
				<th><?php _e('Description', 'theme-options'); ?></th>
				<th><?php _e('Tags', 'theme-options'); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			for ($r=0; $r<count($image_data[0]); $r++) {
				echo "<tr class='"; 
					switch ($r%2) { case 1: echo "alternate"; break; case 2: echo ""; break; }
				echo "'>";
				echo "<th class='manage-column column-cb check-column'><input type='checkbox' value='" . $image_data[0][$r] . "' name='checked[]'></th>";
				echo "<td><a href='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'><img class='theme_image' title='" . $image_data[0][$r] . "' src='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'/></a><br />";
				if ($type == 'inserter') {
					echo "<span class='inline' title='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "' ><a title='Select this image.' href='" . $_GET['url'] . "/wp-content/plugins/theme-options/snippets/Image_Management.php?item=" . $_GET['item'] . "&amp;image=" . $image_data[0][$r] . "&amp;tab=image&amp;url=" . $_GET['url'] . "'>Select</a> | </span>";
				}
				else {
					echo "<span class='inline'><a title='Edit this image group' href='themes.php?page=image_management_page&action=edit&image=" . $image_data[1][$r] . "'>" . __('Edit', 'theme-options') . "</a> | </span>";
					echo "<span class='delete'><a href='themes.php?page=image_management_page&action=delete&image=" . $image_data[1][$r] . "' title='Delete this image' class='submitdelete'>" . __('Delete', 'theme-options') . "</a> | </span>";
				}
				echo "<span class='view'><a rel='permalink' title='View this image' href='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'>" . __('View', 'theme-options') . "</a></span>";
				echo "</td>";
				echo "<td><strong>" . $image_data[0][$r] . "</strong></td>";
				echo "<td>" . $image_data[2][$r] . "</td>";
				echo "<td>" . $image_data[3][$r] . "</td>";
				echo "<td>";
				echo implode(", ", $image_data[4][$r]);
				echo "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
<?php
}

function create_images_table($table_name) {
	global $wpdb;
	if (!$table_name) {
		$table_name = $wpdb->prefix . "theme_options_images";
	}
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		if (mkdir(IMAGE_MGMT_IMAGE_DIR, 0700)) {
			$sql = "CREATE TABLE " . $table_name . " (
			name text NOT NULL,
			url text NOT NULL, 
			size text NOT NULL, 
			description text NOT NULL,
			tags text NOT NULL
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //Get dbDelta function to insert sql data
			dbDelta($sql); //Insert sql data through special WordPress function
		}
	}
	else {
		return 'permission_denied';
	}
}

function fetch_image_mgmt_images($size = 0, $filter_tag = 0, $table_name) {
	global $wpdb;
	if (!$table_name) {
		$table_name = $wpdb->prefix . "theme_options_images";
	}
	create_images_table($table_name);
	// Get Data and filter out all but a single Author if requested
	if ($size) {
		$names = $wpdb->get_col("SELECT name FROM " . $table_name . " WHERE size='" . $size . "'");
		$urls = $wpdb->get_col("SELECT url FROM " . $table_name . " WHERE size='" . $size . "'");
		$sizes = $wpdb->get_col("SELECT size FROM " . $table_name . " WHERE size='" . $size . "'");
		$descriptions = $wpdb->get_col("SELECT description FROM " . $table_name . " WHERE size='" . $size . "'");
		$pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name . " WHERE size='" . $size . "'");
	}
	else {
		$names = $wpdb->get_col("SELECT name FROM " . $table_name);
		$urls = $wpdb->get_col("SELECT url FROM " . $table_name);
		$sizes = $wpdb->get_col("SELECT size FROM " . $table_name);
		$descriptions = $wpdb->get_col("SELECT description FROM " . $table_name);
		$pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name);
	}

	$images = array();
	foreach ((array)$pre_images as $group) {
		$images[] = unserialize($group);
	}
	$all_sizes = $wpdb->get_col("SELECT size FROM " . $table_name);
	$all_pre_tags = $wpdb->get_col("SELECT tags FROM " . $table_name);

	// Get list of all Authors and Tags
	$size_list = array();
	foreach ((array)$all_sizes as $size) {
		if (!in_array($size, $size_list)) $size_list[] = $size;
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
		$fetch = array( array(), array(), array(), array(), array(), $size_list, $tag_list );
		for ($f=0; $f<count($names); $f++) {
			if (in_array($filter_tag, $tags[$f])) {
				$fetch[0][] = $names[$f];
				$fetch[1][] = $urls[$f];
				$fetch[2][] = $sizes[$f];
				$fetch[3][] = $descriptions[$f];
				$fetch[4][] = $tags[$f];
			}
		}
	}
	else {
		// Put data together
		$fetch = array();
		$fetch[] = $names;
		$fetch[] = $urls;
		$fetch[] = $sizes;
		$fetch[] = $descriptions;
		$fetch[] = $tags;
		$fetch[] = $size_list;
		$fetch[] = $tag_list;
	}
	return $fetch;
}

function add_images($table_name, $name, $url, $size, $description, $tags) {
	global $wpdb;
	create_images_table($table_name);
	$insert = "INSERT INTO " . $table_name .
		" (name, url, size, description, tags) " .
		"VALUES ('" . $wpdb->escape($name) . "', '" . $wpdb->escape($url) . "', '" . $wpdb->escape($size) . "', '" . $wpdb->escape($description) . "', '" . $wpdb->escape(serialize($tags)) . "')";
	return $wpdb->query( $insert );
}

function delete_images($table_name, $url) {
	if (is_writable(IMAGE_MGMT_IMAGE_DIR)) {
		global $wpdb;
		$insert = "DELETE FROM " . $table_name . " WHERE url='" . $url . "'";
		$result = $wpdb->query( $insert );

		unlink(IMAGE_MGMT_IMAGE_DIR . $url); // Delete the image file
		return $result;
	}
	else {
		define('THEME_OPTIONS_POST_RESULTS', 'persmission_denied');
	}
}

function edit_images($table_name, $name, $url, $size, $description, $tags) {
	global $wpdb;
	$insert = "UPDATE " . $table_name . " SET ";
	$insert .= "name='" . $name . "'";
	if ($size != 0)	$insert .= ", size='" . $size . "'";
	$insert .= ", description='" . $description . "'";
	$insert .= ", tags='" . serialize($tags) . "'";
	$insert .= " WHERE url='" . $url . "'";
	return $wpdb->query( $insert );
}

function image_management_inserter($image_name) {
	?>
	<a title="Select an Image" class="thickbox" href="<?php echo THEME_SNIPPETS_URL; ?>Image_Management/inserter.php?item=<?php echo $image_name; ?>&amp;tab=image&amp;url=<?php bloginfo('url'); ?>"><img alt="Add an Image" src="images/media-button-image.gif"/></a>
	<?php
}
add_action('image_url_input', 'image_management_inserter');

?>
