<?php
/*
name: Format
author: Dan Cole
url: http://dan-cole.com/
description: Change the font family and font size of your whole site or key elements 
tags: font, format, font family, font size
*/

function format_font_default_elements($items = array()) {
  array_push($items, "tag:html", "tag:h1", "tag:h2", "tag:h3", "tag:h4", "tag:p", "tag:blockquote", "tag:pre", "tag:code", "tag:a");
  return $items;
}
add_filter('format_font_elements', 'format_font_default_elements', 10, $items);

function format_snippet_page() {
  
?>
  <div class='wrap'>
  <h2><?php _e('Format Text'); ?></h2>
  <p><?php _e('The content of your site is wrapper in HTML tags. Using CSS, this content can be styled / formatted. Fill as may of the fields below as you want to change the look of elements within your site. '); ?></p>
  <form name='options-page' method='post' action='themes.php?page=format_snippet_page'>
  <div id='poststuff'>
    <?php
    $items = array();
    $items = apply_filters('format_font_elements', $items);
    for ($i = 0; $i < count($items); $i++) {
      $name = explode(":", $items[$i]);
      $name = trim($name[1]);
      ?>
      <div class="postbox">
      <h3><?php echo $name; ?></h3>
      <div class="inside">
      <table class="form-table" id="format_<?php echo $name; ?>_table">
        <?php format_snippet_options($name); ?>
      </table>
      </div>
      </div><!-- End div class='postbox' -->
      <?php
    }
    ?>
  </div><!-- End div id='poststuff' -->
  <p class='submit'>
    <input type='hidden' name='action' value='save' />
    <input type='submit' name='Submit' value="<?php _e('Save Options', 'mt_trans_domain' ) ?>" />
    </p>
  </form>
  </div><!-- End div class='wrap' -->
<?php

  return array($favicon_name);
}

function attach_format_snippet_page() {
  add_theme_page('Format Font', 'Format Font', 8, format_snippet_page, format_snippet_page);
}
add_action('admin_menu', 'attach_format_snippet_page');

function format_snippet() {
  $items = array();
  $items = apply_filters('format_font_elements', $items);
?>
<style>
<?php
  for ($i = 0; $i < count($items); $i++) {
    $name = explode(":", $items[$i]);
    $name = trim($name[1]);
    if (trim($name[0]) == "tag") {
    }
    elseif (trim($name[0]) == "id") {
      echo "#";
    }
    elseif (trim($name[0]) == "class") {
      echo ".";
    }

    echo $name . " { ";
      if (get_option($name . '_font-family') != NULL ) {
        echo "font-family: " . get_option($name . '_font-family') . "; ";
      }
      if (get_option($name . '_font-size') != NULL ) {
        echo "font-size: " . get_option($name . '_font-size') . "; ";
      }
      if (get_option($name . '_font-weight') != NULL ) {
        echo "font-weight: " . get_option($name . '_font-weight') . "; ";
      }
      if (get_option($name . '_font-style') != NULL ) {
        echo "font-style: " . get_option($name . '_font-style') . "; ";
      }
      if (get_option($name . '_line-height') != NULL ) {
        echo "line-height: " . get_option($name . '_line-height') . "; ";
      }
      do_action('format_snippet_css', $name);
    echo " }\n";
  }
?>

</style>
<?php
}
add_action( "wp_head", 'format_snippet' );

function delete_format_snippet($name) {
  if ($name == 'Format') {
    $items = array();
    $items = apply_filters('format_font_elements', $items);

    $fields = array('_font-family', '_font-size', '_font-weight', '_font-style', '_line-height');

    for ($i = 0; $i < count($items); $i++) {
      $name = explode(":", $items[$i]);
      $name = trim($name[1]);
      for ($f = 0; $f < count($fields); $f++) {
        delete_option($name . $fields);
      }
    }
    do_action('delete_format_snippet');
  }
}
add_action('delete_snippet', 'delete_format_snippet');

function format_snippet_options($name) {
?>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $name . '_font-family'; ?>"><?php echo _e('Font-Family'); ?></label></th>
    <td>
      <input id="font-family" type="text" name="<?php echo $name . '_font-family'; ?>" value="<?php echo get_option($name . '_font-family'); ?>" class="regular-text" />
      <span class="setting-description"><?php _e('Needs to be a list of font family names and/or generic family names. Field will autocomplete.'); ?></span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $name . '_font-size'; ?>"><?php echo _e('Font-Size'); ?></label></th>
    <td>
      <input type="text" name="<?php echo $name . '_font-size'; ?>" value="<?php echo get_option($name . '_font-size'); ?>" class="small-text" /><b>em</b>. 
      <span class="setting-description"><?php _e('Units are <b>em</b>, which is a multipling scale size, based on the inherited font size.'); ?></span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $name . '_font-weight'; ?>"><?php echo _e('Font-Weight'); ?></label></th>
    <td>
      <select name="<?php echo $name . '_font-weight'; ?>">
          <?php $value = get_option($name . '_font-weight'); ?>
          <option value=""<?php echo ($value == '') ? " selected='selected'" : ""; ?>><?php echo _e('Inherit'); ?></option>
          <option value="normal"<?php echo ($value == 'normal') ? " selected='selected'" : ""; ?>><?php echo _e('normal'); ?></option>
          <option value="bold"<?php echo ($value == 'bold') ? " selected='selected'" : ""; ?>><?php echo _e('bold'); ?></option>
      </select>
      <span class="setting-description"><?php _e('Changes the thickness of the letters.'); ?></span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $name . '_font-style'; ?>"><?php echo _e('Font-Style'); ?></label></th>
    <td>
      <select name="<?php echo $name . '_font-style'; ?>">
          <?php $value = get_option($name . '_font-style'); ?>
          <option value=""<?php echo ($value == '') ? " selected='selected'" : ""; ?>><?php echo _e('Inherit'); ?></option>
          <option value="normal"<?php echo ($value == 'normal') ? " selected='selected'" : ""; ?>><?php echo _e('normal'); ?></option>
          <option value="italic"<?php echo ($value == 'italic') ? " selected='selected'" : ""; ?>><?php echo _e('italic'); ?></option>
          <option value="oblique"<?php echo ($value == 'oblique') ? " selected='selected'" : ""; ?>><?php echo _e('oblique'); ?></option>
      </select>
      <span class="setting-description"><?php _e(''); ?></span>
    </td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $name . '_line-height'; ?>"><?php echo _e('Line-Height'); ?></label></th>
    <td>
      <input type="text" name="<?php echo $name . '_line-height'; ?>" value="<?php echo get_option($name . '_line-height'); ?>" class="small-text" />
      <span class="setting-description"><?php _e('Sets the distance between lines. Can be <b>normal</b>, number, length, or percentage.'); ?></span>
    </td>
  </tr>
  <?php do_action('format_snippet_options', $name);
}

function format_snippet_javascript() {
  $data = apply_filters('format_snippet_font_family_data', "");
?>
<script src="<?php echo THEME_OPTIONS_URL; ?>library/js/jquery-autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
  jQuery(document).ready(function(){
    var font_family_data = '<?php echo $data; ?>'.split('; ');
    jQuery("#font-family").autocomplete(font_family_data);
  });
</script>
<?php
}
add_action('admin_head', 'format_snippet_javascript');

function format_snippet_default_fonts($data = "") {
  $data .= 'Arial, "Helvetica Neue", Helvetica, sans-serif; ';
  $data .= 'Cambria, Georgia, Times, "Times New Roman", serif; ';
  $data .= '"Courier New", Courier, monospace; ';
  $data .= 'Georgia, Times, "Times New Roman", serif; ';
  $data .= 'Helvetica, "Helvetica Neue", Arial, sans-serif; ';
  $data .= 'Verdana, Geneva, Tahoma, sans-serif; ';
  return $data;
}
add_filter('format_snippet_font_family_data', 'format_snippet_default_fonts', $data);

function format_snippet_post() {
  if (isset($_GET['page']) && $_GET['page'] == 'format_snippet_page' && isset($_REQUEST['action'])) { 
    $task = 'passed';
    if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];

    if ( in_array($_REQUEST['action_key'], array('activate-selected', 'deactivate-selected', 'delete-selected')) ) {
      $action = $_REQUEST['action_key'];
    }

    if( !empty($action) ) {
      switch( $action ) {
        case 'save':
          $other_fields = array('action', 'Submit');
          $other_fields = apply_filters('format_snippet_post_other_fields', $other_fields);
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
}
add_action('theme_options_post', 'format_snippet_post');
?>
