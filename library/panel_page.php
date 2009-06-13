<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */
function theme_options_panel_page() {
	echo "<div class='wrap'>";
	echo "<h2>"; _e('Theme Options Panel'); echo "</h2>";
	if (THEME_OPTIONS_POST_RESULTS == 'false') {
		echo "<div class='error'><p>"; _e('There was a problem while process the information you submitted.'); echo "</p></div>";
	}
	elseif (THEME_OPTIONS_POST_RESULTS == 'persmission_denied') {
		echo "<div class='error'><p>"; _e('You did <b>not</b> have the correct server permissions to complete the task.'); echo "</p></div>";
	}

	if ($_GET['action'] == 'edit' || (THEME_OPTIONS_POST_RESULTS == 'failed' && $_POST['modify'])) {
	?>
		<h3><?php _e('Edit a Code Snippet'); ?></h3>
		<p><?php _e("Use WordPress's Hook <a href='http://codex.wordpress.org/Plugin_API#Actions'>Actions</a> and <a href='http://codex.wordpress.org/Plugin_API#Filters'>Filters</a> to place functions with your code in a desired location."); ?></p>
		<form name='theme-options-panel' method='post' action='themes.php?page=theme_options_panel_page'>

		<textarea class="large-text codepress php autocomplete-off" rows="15" name="code" id="code"><?php echo get_snippet_code($_GET['name']); ?></textarea>
		<p><?php _e('Use two spaces instead of a tab.'); ?></p>

		<p class='submit'>
		<input type='hidden' name='name' value="<?php echo $_GET['name']; ?>" />
		<input type='hidden' name='action' value='modify' />
		<input type='submit' name='Submit' value="<?php _e('Update Code Snippet', 'mt_trans_domain' ) ?>" onclick="code.toggleEditor()" />
		</p>
		</form>
	<?php
	}
	else {
		$sortby = 'name';
		$filters = array('status'=>$_POST['status_filter'], 'author'=>$_POST['author_filter'], 'type'=>$_POST['type_filter'], 'tags'=>$_POST['tag_filter']);
		$table_data = fetch_snippets($sortby, $filters);
		$snippets_data = $table_data['snippets'];
		$authors_list = $table_data['authors'];
		$tags_list = $table_data['tags'];
		echo "<p>"; _e('This page allows snippets of code, which customize the design and functionality of your website, to be managed. If the snippet has options for you to select and it is activated, they may appear in the <b>Snippet Options</b> page or create their own page under <b>Appearance</b>.'); echo "</p>";
		echo "<form name='options' method='post' action='themes.php?page=theme_options_panel_page'>";
		wp_nonce_field('update-options');
	?>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action_key">
					<option value="0" selected="selected"><?php _e('Bulk Actions'); ?></option>
					<option value="activate-selected"><?php _e('Activate'); ?></option>
					<option value="deactivate-selected"><?php _e('Deactivate'); ?></option>
					<option value="delete-selected"><?php _e('Delete'); ?></option>
				</select>
				<input type="submit" value="<?php _e('Apply'); ?>" name="doaction" id="doaction" class="button-secondary action" />
				<select name="status_filter">
					<option value=""<?php echo ($_POST['status_filter'] == '') ? " selected='selected'" : ''; ?>><?php _e('All Code Snippets'); ?></option>
					<option value="active"<?php echo ($_POST['status_filter'] == 'active') ? " selected='selected'" : ''; ?>><?php _e('Active Code Snippets'); ?></option>
					<option value="deactive"<?php echo ($_POST['status_filter'] == 'deactive') ? " selected='selected'" : ''; ?>><?php _e('Deactive Code Snippets'); ?></option>
				</select>
				<select name="author_filter">
					<option value=""<?php echo ($_POST['author_filter'] == '') ? " selected='selected'" : ''; ?>><?php _e('All Authors'); ?></option>
					<?php
					for ($a = 0; $a < count($authors_list); $a++) {
						echo "<option value='" . $authors_list[$a] . "'";
						echo ($_POST['author_filter'] == $authors_list[$a]) ? " selected='selected'" : '';
						echo ">" . $authors_list[$a] . "</option>";
					}
					?>
				</select>
				<select name="type_filter">
					<option value=""<?php echo ($_POST['type_filter'] == '') ? " selected='selected'" : ''; ?>><?php _e('All Storage Types'); ?></option>
					<option value="Database"<?php echo ($_POST['type_filter'] == 'Database') ? " selected='selected'" : ''; ?>><?php _e('Stored in Database'); ?></option>
					<option value="File"<?php echo ($_POST['type_filter'] == 'File') ? " selected='selected'" : ''; ?>><?php _e('Stored in Files'); ?></option>
				</select>
				<select name="tag_filter">
					<option value=""<?php echo ($_POST['tag_filter'] == '') ? " selected='selected'" : ''; ?>><?php _e('All Tags'); ?></option>
					<?php
					for ($a = 0; $a < count($tags_list); $a++) {
						echo "<option value='" . $tags_list[$a] . "'";
						echo ($_POST['tag_filter'] == $tags_list[$a]) ? " selected='selected'" : '';
						echo ">" . $tags_list[$a] . "</option>";
					}
					?>
				</select>
				<input type="submit" value="<?php _e('Filter'); ?>" name="dofilter" id="dofilter" class="button-secondary action" />
			</div>
		</div>
		<table class='widefat'>
			<thead>
				<tr>
					<th class='check-column'><input type='checkbox'></th>
					<th class='name-column'><?php _e('Name'); ?></th>
					<th class='author-column'><?php _e('Author'); ?></th>
					<th><?php _e('Description'); ?></th>
					<th><?php _e('Tags'); ?></th>
					<th><?php _e('Type'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class='check-column'><input type='checkbox'></th>
					<th class='name-column'><?php _e('Name'); ?></th>
					<th class='author-column'><?php _e('Author'); ?></th>
					<th><?php _e('Description'); ?></th>
					<th><?php _e('Tags'); ?></th>
					<th><?php _e('Type'); ?></th>
				</tr>
			</tfoot>
			<tbody class="theme-options">
				<?php
				$active_snippets = (array)get_option('active_snippets');
				for ($s=0; $s<count($snippets_data); $s++) {
					echo "<tr class='";
						if ($snippets_data[$s]['status'] == 'failed') echo "failed ";
						elseif (in_array($snippets_data[$s]['name'], $active_snippets)) echo "active ";
					echo "'>";
					echo "<th class='manage-column column-cb check-column'><input type='checkbox' value='" . $snippets_data[$s]['name'] . "' name='checked[]'></th>";
					echo "<td>";
						echo "<strong>" . $snippets_data[$s]['name'] . "</strong>";
						echo "<div class='row-actions-visible'>";
							echo (in_array($snippets_data[$s]['name'], $active_snippets)) ? "<a href='" . get_bloginfo('siteurl') . "/wp-admin/themes.php?page=theme_options_panel_page&action=deactivate&name=" . $snippets_data[$s]['name'] . "'>Deactivate</a>" : "<a href='" . get_bloginfo('siteurl') . "/wp-admin/themes.php?page=theme_options_panel_page&action=activate&name=" . $snippets_data[$s]['name'] . "'>Activate</a>";
							echo " | ";
							echo "<a href='" . get_bloginfo('siteurl') . "/wp-admin/themes.php?page=theme_options_panel_page&download=" . $snippets_data[$s]['name'] . "'>Download</a>";
							echo " | ";
							echo "<a href='" . get_bloginfo('siteurl') . "/wp-admin/themes.php?page=theme_options_panel_page&action=copy&name=" . $snippets_data[$s]['name'] . "'>Copy</a>";
							echo " | ";
							echo "<a href='" . get_bloginfo('siteurl') . "/wp-admin/themes.php?page=theme_options_panel_page&action=edit&name=" . $snippets_data[$s]['name'] . "'>Edit</a>";
						echo "</div>";
					echo "</td>";
					echo "<td><a href='" . $snippets_data[$s]['url'] . "'>" . $snippets_data[$s]['author'] . "</a></td>";
					echo "<td>" . $snippets_data[$s]['description'] . "</td>";
					echo "<td>" . $snippets_data[$s]['tags'] . "</td>";
					echo "<td>" . $snippets_data[$s]['type'] . "</td>";
					echo "</tr>";
				}
				?>
			</tbody>
		</table>
		<input type="hidden" name="action" value="apply" />
		</form>
		<hr />

		<h3><?php _e("Import A Code Snippet"); ?></h3>
		<p><?php _e("PHP Files that include the standard code header can be uploaded and managed with the Theme Options Plugin."); ?></p>
		<form enctype="multipart/form-data" name="import" method="post" action="themes.php?page=theme_options_panel_page">
		<h4>Upload the PHP File:</h4>
		<input type="hidden" value="2097152" name="max_file_size"/>
		<input name="snippet_file" type="file" />
		<input type="hidden" name="action" value="import" />
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Upload', 'mt_trans_domain' ) ?>" />
		</p>
		</form>
		<hr />


		<h3><?php _e('Add Code Snippet'); ?></h3>
		<p><?php _e("A general template is provided by default. It is recommended that you fill out the header, which is in a multi-line comment, but it is completely optional. Use WordPress's Hook <a href='http://codex.wordpress.org/Plugin_API#Actions'>Actions</a> and <a href='http://codex.wordpress.org/Plugin_API#Filters'>Filters</a> to place functions with your functions in a desired location."); ?></p>
		<form name='options' method='post' action='themes.php?page=theme_options_panel_page'>

		<textarea class="large-text codepress php autocomplete-off" rows="15" name="code" id="code"><?php 
		if (THEME_OPTIONS_POST_RESULTS == 'failed') {
			echo stripslashes($_POST['code']);
		}
		else { ?>&lt;?php 
/*
name: 
author: 
url: 
description:  
tags: 
*/
function some_function_name() {
  
}
?&gt;<?php }

		?></textarea>
		<p><?php _e('Use two spaces instead of a tab. '); ?></p>

		<p class="submit">
		<input type='hidden' name='action' value='add' />
		<input type="submit" name="Submit" value="<?php _e('Add Code Snippet', 'mt_trans_domain' ) ?>" onclick="code.toggleEditor()" />
		</p>
		</form>

    <?php hook_converter_options(); ?>

	<?php
	}
	?>

	</div>
	<?php 
} // end function parallel_snippets_page

function hook_converter_options() {
  $converters = array();
  $active_conveters = array();
  $dir_handle = opendir(THEME_HOOKS_DIR);
	while (false !== ($file_name = readdir($dir_handle))) {
		if ( !in_array($file_name, array('.', '..', "", null)) && substr($file_name, -1) != "~" ) {
      $name = substr($file_name, 0, -4);
      $converters[] = $name;
      if (get_option($name) == "yes") {
        $active_converters[] = $name;
      }
    }
  }
  closedir($dir_handle);

  if (!empty($converters)) {
?>
  <form name='options' method='post' action='themes.php?page=theme_options_panel_page'>
  <div id="poststuff">
  <div class="postbox">
  <h3><?php _e('Hook Converter'); ?></h3>
  <div class="inside">
  <p><?php _e('Below is a list of files found that will convert one convention of hook names into another for all active snippets. These only need to be on if your using snippets designed for a different theme than the one your using.'); ?></p>
  <table class="form-table" id="hook_converter_table">
<?php
  foreach ($converters as $converter) {
  ?>
  <tr valign="top">
    <th scope="row"><label for="<?php echo $converter; ?>"><?php echo $converter; ?></label></th>
    <td>
      <select name="<?php echo $converter; ?>">
          <?php $value = get_option($converter); ?>
          <option value="no"<?php echo ($value == 'no') ? " selected='selected'" : ""; ?>><?php echo _e('No'); ?></option>
          <option value="yes"<?php echo ($value == 'yes') ? " selected='selected'" : ""; ?>><?php echo _e('Yes'); ?></option>
      </select>
      <span class="setting-description"><?php _e(''); ?></span>
    </td>
  </tr>
  <?php
  }
  ?>
  </table>
	<input type="hidden" name="action" value="save" />
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Save', 'mt_trans_domain' ) ?>" />
	</p>
  <p><?php _e('SNHT stands for Standard Naming of Hooks in Themes and is the only hook naming convention used in default snippets. '); ?></p>
  </div>
  </div><!-- End div class='postbox' -->
  </div>
  </form>
  <?php
  }
}
/*
 * Every single thing I do, has with it a secret, part of a careful plan, to hideway between the lines, the pieces to my puzzle.
 */
?>
