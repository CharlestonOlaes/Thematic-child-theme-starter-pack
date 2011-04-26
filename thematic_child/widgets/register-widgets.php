<?php
/****
 *
 *  This piece of code located in widgets-extentions.php line 262
 *  shows that everything in this folder with the php extention will
 *  be included
 *  
 *  $widgets_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_stylesheet() . '/widgets');
 *      if ((TEMPLATENAME != THEMENAME) && ($widgets_dir)) {
 *              while(($widgetFile = $widgets_dir->read()) !== false) {
 *                     if (!preg_match('|^\.+$|', $widgetFile) && preg_match('|\.php$|', $widgetFile))
 *                              include(ABSPATH . '/wp-content/themes/' . get_stylesheet() . '/widgets/' . $widgetFile);
 *              }
 *      }
 *
 * Simply include an array of options and call the wordpress's register_sidebar();
 * 
 * ex: -----------------------
 * $args = array(
 *  'name'          => ('Above Content'),
 *	'id'            => 'child-above-content',
 *	'description'   => 'Widget area for content before index loop',
 *	'before_widget' => thematic_before_widget(),
 *	'after_widget'  => thematic_after_widget(),
 *	'before_title'  => thematic_before_title(),
 *	'after_title'   => thematic_after_title()
 * );
 *
 * register_sidebar($args);
 * ---------------------------
 * 
****/
?>