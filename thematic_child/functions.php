<?php
//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args');

// Unleash the power of Thematic's comment form
//
// define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);

// Unleash the power of Thematic's feed link functions
//
// define('THEMATIC_COMPATIBLE_FEEDLINKS', true);

// Unleash the power of Thematic's dynamic classes

define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
define('THEMATIC_COMPATIBLE_POST_CLASS', true);

/*
Define paths for easy access
*/
define('CHILD_THEMATIC_INCLUDE_PATH', STYLESHEETPATH.'/library/includes/', true);
define('CHILD_THEMATIC_SCRIPT_PATH', get_bloginfo('stylesheet_directory') . '/library/javascripts/', true);
define('CHILD_THEMATIC_STYLE_PATH', get_bloginfo('stylesheet_directory') . '/library/styles/', true);

require_once(CHILD_THEMATIC_INCLUDE_PATH . 'functions-helper.php');
require_once(CHILD_THEMATIC_INCLUDE_PATH . 'functions-admin.php');
require_once(CHILD_THEMATIC_INCLUDE_PATH . 'functions-biolerplate.php');
?>