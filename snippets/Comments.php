<?php
/*
name: Comments
author: Dan Cole
url: http://dan-cole.com/
description: Highlight different comments based who wrote them and where they are.  
tags: comments, comment highlighting, author
*/

function comments_snippet_page() {
  $author_name = "comments-author";
  $author = get_option($author_name);
  $even_name = "comments-even";
  $even = get_option($even_name);
  $odd_name = "comments-odd";
  $odd = get_option($odd_name);
?>
  <div class="postbox">
    <h3><?php _e('Comments'); ?></h3>
    <div class="inside">
      <table class="form-table" id="favicon_table">
        <tr valign="top">
          <th scope="row"><label for="<?php echo $author_name; ?>"><?php _e("Author's Comment Background Color"); ?></label></th>
          <td>
            <input type="text" name="<?php echo $author_name; ?>" value="<?php echo $author; ?>" />
            <span class="setting-description"><?php echo apply_filters('pick_color', '', 'comments'); ?><?php _e('Color in HEX form or as a standard web color name.'); ?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $even_name; ?>"><?php _e("Even Comment Background Color"); ?></label></th>
          <td>
            <input type="text" name="<?php echo $even_name; ?>" value="<?php echo $even; ?>" />
            <span class="setting-description"><?php echo apply_filters('pick_color', '', 'comments'); ?><?php _e('Color in HEX form or as a standard web color name.'); ?></span>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="<?php echo $odd_name; ?>"><?php _e("Odd Comment Background Color"); ?></label></th>
          <td>
            <input type="text" name="<?php echo $odd_name; ?>" value="<?php echo $odd; ?>" />
            <span class="setting-description"><?php echo apply_filters('pick_color', '', 'comments'); ?><?php _e('Color in HEX form or as a standard web color name.'); ?></span>
          </td>
        </tr>
      </table>
    </div>
  </div>
<?php
}
add_action('theme_options_snippet_options', 'comments_snippet_page');

function comments_snippet() {
  $author = get_option('comments-author');
  if ($author != NULL) {
  ?>
#comments-list ol li.bypostauthor, #comments li.entry-author { background-color: <?php echo $author; ?>; }
  <?php
  }
  $even = get_option('comments-even');
  if ($even != NULL) {
  ?>
.even, #comments-list .comment, #comments .comment { background-color: <?php echo $even; ?>; }
  <?php
  }
  $odd = get_option('comments-odd');
  if ($odd != NULL) {
  ?>
.odd, #comments-list li.alt, #comments li.alt { background-color: <?php echo $odd; ?>; }
  <?php
  }
}
add_action('theme_options_snippets_css', 'comments_snippet');

function delete_comments_snippet($name) {
  if ($name == 'Comments') {
    delete_option('comments-author');
    delete_option('comments-even');
    delete_option('comments-odd');
  }
}
add_action('delete_snippet', 'delete_comments_snippet');

function default_comment_color_options($output, $section) {
  $output .= "<span class='color_member' title='#FFFFFF'><span title='#FFFFFF' class='color_box' style='padding: 0 10px 10px 10px; background: #FFFFFF;'>&nbsp;</span></span>";
  $output .= "<span class='color_member' title='#E7F8FB'><span title='#E7F8FB' class='color_box' style='padding: 0 10px 10px 10px; background: #E7F8FB;'>&nbsp;</span></span>";
  $output .= "<span class='color_member' title='#FFFFCC'><span title='#FFFFCC' class='color_box' style='padding: 0 10px 10px 10px; background: #FFFFCC;'>&nbsp;</span></span>";
  $output .= "<span class='color_member' title='#F1F1F1'><span title='#F1F1F1' class='color_box' style='padding: 0 10px 10px 10px; background: #F1F1F1;'>&nbsp;</span></span>";
  return $output;
}
add_filter('pick_color', 'default_comment_color_options', 10, 3);
?>
