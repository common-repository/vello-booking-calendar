<?php

/*
* Plugin Name: Vello Booking Calendar
* Description: Plugin to Show Vello Booking Calendar with Shortcode
* Version: 1.0.0
* Author: Vello
* Author URI: https://vello.fi/en
* Text Domain: vello-booking-calendar
* Domain Path: /lang
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there! Not much I can do when called directly.';
	exit;
}
// Embed Vello Booking Calendar from https://vello.fi
// Creates div and inserts iframe within to load preconfigured calendar
function vellofi_shortcodes_init() {
  function vellofi_shortcode($atts = [], $content = null) {
    $company_id = get_option('vellofi_company');

    if(isset($company_id) && strlen($company_id) > 3) {
      $content = '<div id="vello-wizard"></div>';
      $content .= '<script type="text/javascript" language="javascript">(function(d,s,i,c,j,a){a=d.getElementsByTagName(s)[0];if(d.getElementById(i))return;j=d.createElement(s);j.id=i;j.async=1;j.setAttribute("data-c",c);j.src="https://static.vello.fi/js/wizard/vwiz.js";a.parentNode.insertBefore(j,a);}(document,"script","vello-wizard-sdk","' . $company_id . '"));</script>';
    } else {
      $content = '<div id="vello-error">Check Vello Settings!</div>';
    }
    
    return $content;
  }
  
  add_shortcode('vello', 'vellofi_shortcode');
}

function vellofi_load_textdomain() {
	load_plugin_textdomain( 'vello-booking-calendar', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

// Wordpress hooks
add_action('init', 'vellofi_shortcodes_init');
add_action('plugins_loaded', 'vellofi_load_textdomain');

// create custom plugin settings menu
add_action('admin_menu', 'vellofi_create_admin_menu');

function vellofi_create_admin_menu() {

	//create new top-level menu
	add_menu_page('Vello Booking Calendar Settings', __( 'Booking Calendar', 'vello-booking-calendar' ), 'administrator', __FILE__, 'vellofi_settings_page' , plugins_url('/images/logo-vello.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'vellofi_register_settings' );
}


function vellofi_register_settings() {
	//register our settings
	register_setting( 'vellofi-plugin-settings-group', 'vellofi_company' );
}

function vellofi_settings_page() {
?>

<div class="wrap">
<h1><?php  _e( 'Vello Booking Calendar', 'vello-booking-calendar' ) ?></h1>

<form method="post" action="options.php">
    <?php settings_fields( 'vellofi-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'vellofi-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php  _e( 'Vello Company ID', 'vello-booking-calendar' ) ?></th>
        <td><input type="text" name="vellofi_company" value="<?php echo esc_attr( get_option('vellofi_company') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
<div>
  <h1><?php  _e( 'Usage', 'vello-booking-calendar' ) ?></h1>
  <p><?php  _e( 'Embed booking calendar using shortcode', 'vello-booking-calendar' ) ?> <code>[vello]</code> <?php  _e( 'on any page or post', 'vello-booking-calendar' ) ?></p>
</div>
<?php } ?>