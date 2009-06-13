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
    <h3><?php _e('Favicon Image'); ?></h3>
    <div class="inside">
      <table class="form-table" id="favicon_table">
        <tr valign="top">
          <th scope="row"><label for="<?php echo $favicon_name; ?>"><?php _e('Favicon Image URL'); ?></label></th>
          <td>
            <?php do_action('favicon_before_input'); ?>
            <input type="text" name="<?php echo $favicon_name; ?>" value="<?php echo $favicon_url; ?>" />
            <?php do_action('favicon_after_input'); ?>
            <span class="setting-description"><?php _e('Image must be in ICO format, not: JPG, GIF, or PNG.'); ?></span>
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

function delete_favicon_snippet($name) {
  if ($name == 'Favicon') {
    delete_option('favicon_url');
  }
}
add_action('delete_snippet', 'delete_favicon_snippet');

?>
