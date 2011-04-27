<?php
/**
* Borrowed some of the HTML5 Biolerplate best practices
* http://html5boilerplate.com
**/

//html5 doctype
function childtheme_create_html5_doctype($content){
    $content = '<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" '.get_bloginfo('language').'> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" '.get_bloginfo('language').'> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" '.get_bloginfo('language').'> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"';
    return $content;
}

add_filter('thematic_create_doctype', 'childtheme_create_html5_doctype');

//due to the thematic template, I had to split the closing if statement from the doctype
function childtheme_lang_attr($content){
    $content = get_bloginfo('language').'><!--<![endif]--';
    return $content;
}

add_filter('language_attributes', 'childtheme_lang_attr');

//content type
function childtheme_create_html5_contenttype($content){
    /*Always force latest IE rendering engine (even in intranet) & Chrome Frame
    Remove this if you use the .htaccess*/
    $content = '<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
    return $content;
}

add_filter('thematic_create_contenttype', 'childtheme_create_html5_contenttype');

//remove the hoverIntent, superfish, supersubs and thematic-dropdowns
//that the thematic theme adds
function childtheme_remove_thematic_scripts($content){
    return $content = childtheme_google_analytics();
}

add_filter('thematic_head_scripts', 'childtheme_remove_thematic_scripts');

//displaying your google analytics code.
//I find it more effecient to include this in the header even though
//the html5 biolerplate puts it in the footer. 
function childtheme_google_analytics(){
    
    //mathiasbynens.be/notes/async-analytics-snippet Change UA-XXXXX-X to be your site's ID
    return '<script>
    var _gaq=[["_setAccount","UA-'.get_option( 'ct_google_UA' ).'"],["_trackPageview"]];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
    g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
    s.parentNode.insertBefore(g,s)}(document,"script"));
    </script>';
  
}

//a function using wp_enqueue_scripts in a loop
function childtheme_register_scripts(){
    
    if(!is_admin()){
        
        //gets the array returned by the childtheme_scripts_array()
        $scripts = childtheme_scripts_array();
        
        foreach( $scripts as $script ){
            
            wp_deregister_script($script['handle']);
            
            wp_register_script(
                $script['handle'],
                $script['src'],
                $script['deps'],
                $script['ver'],
                $script['infooter']
            );
            
            wp_enqueue_script($script['handle']);
        }
    }
    
}

//by hooking it in the wp_enqueue_scripts hook instead of the
//init hook, we can use conditional statements like is_home()
//or is_page()
add_action('wp_enqueue_scripts', 'childtheme_register_scripts');

//returns an array of scripts to be registered in childtheme_register_scripts()
function childtheme_scripts_array(){
    
    global $is_IE;
    $jquery = 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js';
    $ver = false;
    $opt = get_option( 'ct_jquery_version' );
    $t_opt = get_option('ct_thematic_scripts');
    
    //checks for what version of jquery you want
    //can be changed in the child options page in the admin menu
    switch( $opt ){
        case 'url' :
            $ver = '1.5.2';
            break;
        
        case 'jquery-1.4.2.min.js' :
            $jquery = CHILD_THEMATIC_SCRIPT_PATH . $opt;
            $ver = '1.4.2';
            break;
        
        case 'jquery-1.4.3.min.js' :
            $jquery = CHILD_THEMATIC_SCRIPT_PATH . $opt;
            $ver = '1.4.3';
            break;
        
        case 'jquery-1.5.1.min.js' :
            $jquery = CHILD_THEMATIC_SCRIPT_PATH . $opt;
            $ver = '1.5.1';
            break;
    }
    
    //includes the mordernizr
    $scripts = array(
        'jquery' => array(
            'handle' => 'jquery',
            'src' => $jquery,
            'ver' => $ver,
            'infooter' => true
        ),
        'modernizr' => array(
            'handle' => 'modernizr',
            'src' => CHILD_THEMATIC_SCRIPT_PATH . 'modernizr-1.7.min.js',
            'ver' => '1.7',
            'infooter' => false
        )
    );
    
    //These are the scripts that have been included in the Thematic theme,
    //The scripts were added without using the wp_enqueue_script() function
    //You can enable or disable these in the Child Options page
    if( $t_opt == 'enable_scripts'){
        
        $path = get_bloginfo('template_directory') . '/library/scripts/';
        
        $scripts = array_merge($scripts, array(
            'hoverIntent' => array(
                'handle' => 'hoverIntent',
                'src' => $path . 'hoverIntent.js',
                'deps' => array('jquery'),
                'infooter' => true
            ),
            'superfish' => array(
                'handle' => 'superfish',
                'src' => $path . 'superfish.js',
                'deps' => array('jquery'),
                'infooter' => true
            ),
            'supersubs' => array(
                'handle' => 'supersubs',
                'src' => $path . 'supersubs.js',
                'deps' => array('jquery'),
                'infooter' => true
            ),
            'thematic-dropdowns' => array(
                'handle' => 'thematic-dropdowns',
                'src' => $path . 'thematic-dropdowns.js',
                'deps' => array('jquery'),
                'infooter' => true
            )
        ));
    }
    
    //this fixes the png stuff
    if($is_IE){
        $scripts = array_merge($scripts, array(
            'dd_belatedpng' => array(
                'handle' => 'dd_belatedpng',
                'src' => CHILD_THEMATIC_SCRIPT_PATH . 'dd_belatedpng.js',
                'infooter' => true
            )
        ));
    }
    
    return $scripts;
    
}

//loads scripts at the very bottom of the page
//I wish there was a better way to do this
function childtheme_other_scripts(){
    global $is_IE;
    if($is_IE)  :
        // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb
        echo '<!--[if lt IE 7 ]><script>DD_belatedPNG.fix("img, .png_bg");</script><![endif]-->';
    
    endif;
}

add_action('thematic_after', 'childtheme_other_scripts');


//clean up the header
function childtheme_clean_header(){
    
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link');
    wp_deregister_script( 'l10n' );
    wp_deregister_script( 'comment-reply' );
    
}

add_action('init', 'childtheme_clean_header');
?>