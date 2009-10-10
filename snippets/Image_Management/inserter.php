<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @website http://dan-cole.com
 * @package 'Image Management' snippet for 'Theme Options', a WordPress plugin
 *
 */

if ($_GET['tab'] && $_GET['item']) {
	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	require_once $root . "/wp-blog-header.php";
	global $wpdb;
	if ($_GET['image']) {
		update_option($_GET['item'], $_GET['image']);
	}
	image_table('inserter');
?>
	<script type='text/javascript'>
	jQuery(document).ready( function() {
		jQuery('.inline a').click(function (e) {
			e.preventDefault();
			var image_name = jQuery(this).parent().attr('title');
			jQuery("input[name='<?php echo $_GET['item']; ?>']").attr("value",image_name);
			tb_remove();
		});
	});
	</script>
	<?php
}	

?>
