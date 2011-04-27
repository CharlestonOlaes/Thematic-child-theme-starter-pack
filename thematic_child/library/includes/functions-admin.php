<?php
/*****************************
 *
 * Adding an admin panel
 *
 * **************************/

//initializes the admin page
add_action('admin_menu', 'childtheme_option_menu_page');

//creates the page and initializes the settings
if( !function_exists( 'childtheme_option_menu_page' ) ) :

function childtheme_option_menu_page(){
    add_submenu_page( 'themes.php',
                    'Child Theme Options',
                    'Child Options',
                    'administrator',
                    'thematic_childtheme_options',
                    'childtheme_option_setup' );
    
    add_action( 'admin_init', 'childtheme_register_settings' );

}

endif;

//creates the form, very simple, no need for detailed explaination
if( !function_exists( 'childtheme_option_setup' ) ) :

function childtheme_option_setup(){
    
    if (!current_user_can('administrator'))  {
	wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    ?>
    
    <div class="wrap">
        <h2>Child Theme Options</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'childtheme_option_settings_group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Jquery Version</th>
                    <td>
                        <select name="ct_jquery_version">
                            <option value="jquery-1.4.2.min.js"
                                <?php selected( get_option( 'ct_jquery_version' ), 'jquery-1.4.2.min.js' ); ?>>
                                Jquery 1.4.2
                            </option>
                            <option value="jquery-1.4.3.min.js"
                                <?php selected( get_option( 'ct_jquery_version' ), 'jquery-1.4.3.min.js' ); ?>>
                                Jquery 1.4.3
                            </option>
                            <option value="jquery-1.5.1.min.js"
                                <?php selected( get_option( 'ct_jquery_version' ), 'jquery-1.5.1.min.js' ); ?>>
                                Jquery 1.5.1
                            </option>
                            <option value="url"
                                <?php selected( get_option( 'ct_jquery_version' ), 'url' ); ?>>
                                Jquery 1.5.2 (CDN)
                            </option>
                        </select>
                    </td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Include thematic scripts</th>
                    <td>UA-<input type="text" name="ct_google_UA" value="<?php
                        echo get_option( 'ct_google_UA' ); ?>" />
                        <small>Do not include the UA</small>
                    </td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Google Analytics code</th>
                    <td><input type="checkbox" name="ct_thematic_scripts" value="enable_scripts" <?php
                        checked( get_option('ct_thematic_scripts'), 'enable_scripts' );
                    ?> />
                        <small>This will include hoverIntent, superfish, supersubs and thematic-dropdowns</small>
                    </td>
                </tr>
                
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
        
        <?php
}

endif;

if( !function_exists( 'childtheme_register_settings' ) ) :

function childtheme_register_settings(){
    register_setting( 'childtheme_option_settings_group', 'ct_jquery_version' );
    register_setting( 'childtheme_option_settings_group', 'ct_google_UA' );
    register_setting( 'childtheme_option_settings_group', 'ct_thematic_scripts' );
}

endif;
?>