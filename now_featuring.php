<?php
/*
 * Now Featuring Wordpress Widget
 *
 * Plugin Name: Now Featuring
 * Plugin URI: http://wp.brahminacreations.com/now-featuring-wordpress-widget/
 * Description: Include featured posts or pages on your sidebar in a variety of ways.
 * 			
 * Author: Brahmina Burgess
 * Version: 0.8
 * Author URI: http://brahminacreations.com
 */

// Define plugin constants
define( 'NF_PLUGIN_FILE', __FILE__ );
define( 'NF_PLUGIN_VERSION', '0.8' );

// Includes
require_once 'includes/assets.php';
require_once 'includes/shortcodes.php';
require_once 'includes/widget.php';
require_once 'includes/load.php';

/*
 * nf_log_message - For development & debuggin
 */
function nf_log_message($message){
	if (WP_DEBUG == true) {
        if (is_array($message) || is_object($message)) {
            error_log("NF..." . print_r($message, true));
        } else {
            error_log("NF... " . $message);
        }
    }
}

?>