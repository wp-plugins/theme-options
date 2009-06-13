<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */

function hook_converter($code) {
  $active_conveters = array();
  $dir_handle = opendir(THEME_HOOKS_DIR);
	while (false !== ($file_name = readdir($dir_handle))) {
		if ( !in_array($file_name, array('.', '..', "", null)) && substr($file_name, -1) != "~" ) {
      $name = substr($file_name, 0, -4);
      if (get_option($name) == "yes") {
        $active_converters[] = $name;
      }
    }
  }
  closedir($dir_handle);
  foreach ((array)$active_converters as $file_name) {
	  $location = THEME_HOOKS_DIR . $file_name . '.txt';
	  $fh = fopen($location, 'rb');
	  $file_data = fread($fh, filesize($location));
	  fclose($fh);
	  $equivalents = explode("\n", $file_data);
	  for ($equ = 0; $equ < count($equivalents); $equ++) {
		  if ($equivalents[$equ] != NULL && substr($equivalents[$equ], 0, 1) != "/") {
			  $line = str_replace("\t\t", "\t", $equivalents[$equ]);
			  $hook_name = explode("\t", $line);
        $code = str_replace(trim($hook_name[0]), trim($hook_name[1]), $code);
      }
    }
  }
  return $code;
}
add_filter('execute_snippet_code', 'hook_converter');

?>
