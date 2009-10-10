<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */
function theme_options_snippets_page() {
	?>
	<div class='wrap'>
	<h2><?php _e('Snippet Options', 'theme-options'); ?></h2>
	<p><?php _e('If activated snippets have a small amount of options they need selected, an approperate section will appear below. However, the snippet may make its own options page if needed. ', 'theme-options'); ?></p>
	<?php
	if (current_user_can('manage_options')) {
		?>
		<form name='options-page' method='post' action='themes.php?page=theme_options_snippets_page'>
		<div id='poststuff'>
			<?php do_action('theme_options_snippet_options'); ?>
		</div><!-- End div id='poststuff' -->
		<p class='submit'>
			<?php wp_create_nonce('theme_options'); ?>
			<?php wp_nonce_field('theme_options'); ?>
			<input type='hidden' name='action' value='save' />
			<input type='submit' name='Submit' value="<?php _e('Save Options', 'theme-options') ?>" />
			</p>
		</form>
		</div><!-- End div class='wrap' -->
		<?php
	}
	else {
		echo '<p>';
		_e('Only people that can manage options can access the content on this page.', 'theme-options');
		echo '</p>';
	}
}
?>
