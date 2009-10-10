<?php
/*
name: Favicon
author: Dan Cole
url: http://dan-cole.com/
description: Point Browsers to you site's Favicon, which appears in the address bar and in bookmarks.
tags: favicon, image
*/

function favicon_snippet_page() {
	$favicon_name = "favicon_url";
	$favicon_url = get_option($favicon_name);
?>
	<div class="postbox">
		<h3><?php _e('Favicon Image', 'theme-options'); ?></h3>
		<div class="inside">
			<table class="form-table" id="favicon_table">
				<tr valign="top">
					<th scope="row"><label for="<?php echo $favicon_name; ?>"><?php _e('Favicon Image URL', 'theme-options'); ?></label></th>
					<td>
						<?php do_action('favicon_before_input'); ?>
						<input type="text" name="<?php echo $favicon_name; ?>" value="<?php echo $favicon_url; ?>" /><?php do_action('image_url_input', $favicon_name); ?>
						<?php do_action('favicon_after_input'); ?>
						<span class="setting-description"><?php _e('Image must be in ICO format, not: JPG, GIF, or PNG.', 'theme-options'); ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?php
}
add_action('theme_options_snippet_options', 'favicon_snippet_page');

function favicon_snippet() {
	$favicon_url = get_option('favicon_url');
	if ($favicon_url != NULL) {
		echo "<link rel='shortcut icon' href='" . $favicon_url . "' type='image/x-icon' />";
	}
}
add_action('wp_head', 'favicon_snippet');

function favicon_snippet_options($list, $name = NULL) {
	if ($name == 'Favicon' || $name == NULL) {
		$list[] = 'favicon_url';
	}
	return $list;
}
add_filter('snippet_options_list', 'favicon_snippet_options');

function favicon_needs_thickbox() {
	add_thickbox();
}
add_action('init', 'favicon_needs_thickbox');

?>
