<?php
/*
name: Post Author
author: Dan Cole
url: http://dan-cole.com/
description: Add an 'about author' section at the end of single posts. You may need to turn on the appropriate hook converting file to get this to work, which can be done at the bottom of this page.	
tags: single post, about, author
*/

function post_author_snippet_page() {
	$location_name = "post_author_location";
	$location = get_option($location_name);
	$image_name = "post_author_image";
	$image = get_option($image_name);
?>
	<div class="postbox">
		<h3><?php _e('Post Author', 'theme-options'); ?></h3>
		<div class="inside">
			<p><?php _e("The actual content of this section comes from the <b>Biographical Info</b> within each author's <b>profile</b> page, which can be accessed through the administation menu under: <b>Users</b>.", 'theme-options'); ?></p>
			<table class="form-table" id="post_author_table">
				<tr valign="top">
					<th scope="row"><label for="<?php echo $location_name; ?>"><?php _e('Post Author Location', 'theme-options'); ?></label></th>
					<td>
						<select name="<?php echo $location_name; ?>">
								<option value="after"<?php echo ($location == 'after') ? " selected='selected'" : ""; ?>><?php echo _e('After', 'theme-options'); ?></option>
								<option value="before"<?php echo ($location == 'before') ? " selected='selected'" : ""; ?>><?php echo _e('Before', 'theme-options'); ?></option>
						</select>
						<span class="setting-description"><?php _e('The location of the section about the author of the post with appear.', 'theme-options'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="<?php echo $image_name; ?>"><?php _e("Display Author's Gravatar", 'theme-options'); ?></label></th>
					<td>
						<input type="radio" name="<?php echo $image_name; ?>" value="yes" <?php echo ($image == 'yes') ? 'checked="checked"': ''; ?> /> <?php _e('Yes', 'theme-options'); ?> 
						<input type="radio" name="<?php echo $image_name; ?>" value="no" <?php echo ($image != 'yes') ? 'checked="checked"': ''; ?> /> <?php _e('No', 'theme-options'); ?> | 
						<span class="setting-description"><?php _e('Gets image from <a href="http://gavatar.com">Gravatar.com</a> or defaults to the same image as in comments.', 'theme-options'); ?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
<?php
}
add_action('theme_options_snippet_options', 'post_author_snippet_page');

function post_author_snippet($input) {
	echo $input;
	if (is_single()) {
		echo "<div id='author_meta'>";
		echo "<h3>" . __('About Author: ', 'theme-options') . get_the_author_meta('nickname') . "</h3>";
		echo "<p>";
		if (get_option("post_author_image") == "yes") {
			echo "<span class='alignleft'>" . get_avatar(get_the_author_meta('ID')) . "</span>";
		}
		echo the_author_meta('description');
		echo "</p>";
		echo "</div>";
	}
}
if (get_option("post_author_location") == "before") add_action('theme_before_content', 'post_author_snippet');
else add_action('theme_after_content', 'post_author_snippet');

function post_author_snippet_options($list, $name = NULL) {
	if ($name == 'Post Author' || $name == NULL) {
		$list[] = 'post_author_location';
	}
	return $list;
}
add_filter('snippet_options_list', 'post_author_snippet_options');

?>
