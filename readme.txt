=== Plugin Name ===
Contributors: dcole07
Donate link: http://dan-cole.com/
Tags: code, manager, extension, theme options, image manager, custom header image, post author, favicon, comments
Requires at least: 2.8
Tested up to: 2.8
Stable tag: 0.1

This plugin manages theme customizations.

== Description ==

This plugin manages theme customization. Everything is managed in the WordPress Backend. Code snippets can be activated, copied, created, deactivated, deleted, edited, and uploaded.

== Installation ==

1. Upload the `theme-options` folder and its contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To use the plugin, go to 'Theme Options Panel' under the 'Appearance' menu in WordPress

== Frequently Asked Questions ==

= Why don't the pre-installed code snippets work? =

Every code snippet needs to use WordPress' Hook actions and filters, but if the hook is not within your theme, these snippets will have no place to attach to.

= What themes will the default snippets work for? =

They will work for any theme that uses the 'Standard Naming of Hooks in Themes', along with Thematic and Hybrid. However, you can alway right your own hook converter file and place it in the '.../plugins/theme-options/hooks/' folder. 

= What is required to create my own code snippet =

Your code will need to be in a PHP functions and placed in the correct location by 'add_action()' or 'add_filter()'. It is recommended that you fill in the default header, which is in a multi-line comment, but it can be done automatically for you. 

= Will my Wordpress break if I write code with errors? =

This plugin will automatically turn off any code snippet if it causes a fatal error, but not necessarily if it has an error or typo. Then on the second page load, that code snippet that failed will not be included and the 'Theme Options Panel' will highlight the failed code snippet.

