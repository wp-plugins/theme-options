<?php
/*
name: Custom Header
author: Dan Cole
url: http://dan-cole.com/
description: Add a background image to the header and change the Title to a logo. 
tags: image, header, background, title, logo
*/

function custom_header_snippet_page() {
  $background_name = "header_background_image";
  $background = get_option($background_name);
  $repeat_name= "header_background_repeat";
  $repeat = get_option($repeat_name);
  $scroll_name= "header_background_scroll";
  $scroll = get_option($scroll_name);
  $align_name= "header_background_align";
  $align = get_option($align_name);

  $logo_name = "header_logo_image";
  $logo = get_option($logo_name);
?>
  <div class="postbox">
    <h3><?php _e('Custom Header'); ?></h3>
    <div class="inside">
      <table class="form-table" id="custom_header_table">
        <tr valign="top">
          <th scope="row"><label for="<?php echo $background_name; ?>"><?php _e('Header Background Image'); ?></label></th>
          <td>
            <input type="text" name="<?php echo $background_name; ?>" value="<?php echo $background; ?>" class="regular-text" />
            <?php do_action('image_url_input', $background_name); ?>
            <select name="<?php echo $repeat_name; ?>">
              <option<?php echo ($repeat == 'repeat') ? ' selected="selected"' : ''; ?> value="repeat"><?php _e('Repeat'); ?></option>
              <option<?php echo ($repeat == 'repeat-x') ? ' selected="selected"' : ''; ?> value="repeat-x"><?php _e('Repeat Horizontally'); ?></option>
              <option<?php echo ($repeat == 'repeat-y') ? ' selected="selected"' : ''; ?> value="repeat-y"><?php _e('Repeat Vertically'); ?></option>
              <option<?php echo ($repeat == 'no-repeat') ? ' selected="selected"' : ''; ?> value="no-repeat"><?php _e('No Repeat'); ?></option>
            </select>
            <select name="<?php echo $scroll_name; ?>">
              <option<?php echo ($scroll == 'fixed') ? ' selected="selected"' : ''; ?> value="fixed"><?php _e('Fixed'); ?></option>
              <option<?php echo ($scroll == 'scroll') ? ' selected="selected"' : ''; ?> value="scroll"><?php _e('Scroll'); ?></option>
            </select>
            <select name="<?php echo $align_name; ?>">
              <option<?php echo ($align == 'top left') ? ' selected="selected"' : ''; ?> value="top left"><?php _e('Top left'); ?></option>
              <option<?php echo ($align == 'top center') ? ' selected="selected"' : ''; ?> value="top center"><?php _e('Top center'); ?></option>
              <option<?php echo ($align == 'top right') ? ' selected="selected"' : ''; ?> value="top right"><?php _e('Top right'); ?></option>
              <option<?php echo ($align == 'center left') ? ' selected="selected"' : ''; ?> value="center left"><?php _e('Center left'); ?></option>
              <option<?php echo ($align == 'center center') ? ' selected="selected"' : ''; ?> value="center center"><?php _e('Center center'); ?></option>
              <option<?php echo ($align == 'center right') ? ' selected="selected"' : ''; ?> value="center right"><?php _e('Center right'); ?></option>
              <option<?php echo ($align == 'bottom left') ? ' selected="selected"' : ''; ?> value="bottom left"><?php _e('Bottom left'); ?></option>
              <option<?php echo ($align == 'bottom center') ? ' selected="selected"' : ''; ?> value="bottom center"><?php _e('Bottom center'); ?></option>
              <option<?php echo ($align == 'bottom right') ? ' selected="selected"' : ''; ?> value="bottom right"><?php _e('Bottom right'); ?></option>
            </select>
            <span class="setting-description"><?php _e('Provide URL of Image in text box.'); ?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $logo_name; ?>"><?php _e('Logo Image'); ?></label></th>
          <td>
            <input type="text" name="<?php echo $logo_name; ?>" value="<?php echo $logo; ?>" class="regular-text""/>
            <?php do_action('image_url_input', $logo_name); ?>
            <span class="setting-description"><?php _e('Provide URL of Image in text box.'); ?></span>
          </td>
        </tr>
      </table>
      <p>Use the <b>Theme Image Management</b> snippet to make uploading the image and getting the image URL easier.</p>
    </div>
  </div>
<?php
}
add_action('theme_options_snippet_options', 'custom_header_snippet_page');

function custom_header() {
  $background_name = "header_background_image";
  $background = get_option($background_name);
  if ($background != NULL) {
    $repeat_name= "header_background_repeat";
    $repeat = get_option($repeat_name);
    $scroll_name= "header_background_scroll";
    $scroll = get_option($scroll_name);
    $align_name= "header_background_align";
    $align = get_option($align_name);

    echo "<style>";
    echo "#header { background: transparent url('" . $background . "') " . $repeat . " " . $scroll . " " . $align . "; }";
    echo "</style>";
  }

  $logo_name = "header_logo_image";
  $logo = get_option($logo_name);
  if ($logo != NULL) {
    remove_action('theme_header', 'theme_site_title');
    remove_action('theme_header', 'theme_site_description');
    add_action('theme_header', 'header_logo_image', 1);

    echo "<style>";
    echo "#site-logo a { background: transparent url('" . $background . "') no-repeat scroll center top; }";
    echo "</style>";
  }
}
add_action('wp_head', 'custom_header');

function delete_custom_header($name) {
  if ($name == 'Custom Header') {
    delete_option('header_background_image');
    delete_option('header_background_repeat');
    delete_option('header_background_scroll');
    delete_option('header_background_align');
    delete_option('header_logo_image');
  }
}
add_action('delete_snippet', 'delete_favicon');

function header_logo_image() {
  echo "<div id='site-logo'><a href='" . get_bloginfo('home') . "'><img alt='" . get_bloginfo('name') . "' src='" . get_option('header_logo_image') . "' /></a></div>";  
}
// header_logo_image() is called in custom_header()

?>
