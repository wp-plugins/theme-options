<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @website http://dan-cole.com
 * @package 'Image Management' snippet for 'Theme Options', a WordPress plugin
 *
 */

if ($_GET['tab'] && $_GET['item']) {
  $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
  require_once "/var/www/html/dancole/wp-blog-header.php";

  global $wpdb;
  if ($_REQUEST["image_table"]) $table_name = $_REQUEST["image_table"];
  else $table_name = "theme_options_images";

  if ($_GET['image']) {
    update_option($_GET['item'], $_GET['image']);
  }

  $filter_size = 0;
  $filter_tag = 0;
  $image_data = fetch_image_mgmt_images($size, $filter_tag, $table_name);
  ?>
  <h3>Select a Theme Image</h3>
  <table class='widefat'>
    <thead>
      <tr>
        <th><?php _e('Image'); ?></th>
        <th><?php _e('Name'); ?></th>
        <th><?php _e('Size (px)'); ?></th>
        <th><?php _e('Description'); ?></th>
        <th><?php _e('Tags'); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th><?php _e('Image'); ?></th>
        <th><?php _e('Name'); ?></th>
        <th><?php _e('Size (px)'); ?></th>
        <th><?php _e('Description'); ?></th>
        <th><?php _e('Tags'); ?></th>
      </tr>
    </tfoot>
    <tbody>
      <?php
      for ($r=0; $r<count($image_data[0]); $r++) {
        echo "<tr class='"; 
          switch ($r%2) { case 1: echo "alternate"; break; case 2: echo ""; break; }
        echo "'>";
        echo "<td><span class='inline' title='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'><a title='Select this image.' href='" . $_GET['url'] . "/wp-content/plugins/theme-options/snippets/Image_Management.php?item=" . $_GET['item'] . "&amp;image=" . $image_data[0][$r] . "&amp;tab=image&amp;url=" . $_GET['url'] . "'><img class='theme_image' title='Select this image.' src='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'/></a></span><br />
<span class='inline' title='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "' ><a title='Select this image.' href='" . $_GET['url'] . "/wp-content/plugins/theme-options/snippets/Image_Management.php?item=" . $_GET['item'] . "&amp;image=" . $image_data[0][$r] . "&amp;tab=image&amp;url=" . $_GET['url'] . "'>Select</a> | </span>
<span class='view'><a rel='permalink' title='View this image in new window.' target='_blank' href='" . IMAGE_MGMT_IMAGE_URL . $image_data[1][$r] . "'>View</a></span>
</td>";
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
  <script type='text/javascript'>
  jQuery(document).ready( function() {
    jQuery('.inline a').attr('href', '#');
    jQuery('.inline a').click(function () { 
      var image_name = jQuery(this).parent().attr('title');
      jQuery("input[name='<?php echo $_GET['item']; ?>']").attr("value",image_name);
      tb_remove();
    });
  });
  </script>
  <?php
}  

?>
