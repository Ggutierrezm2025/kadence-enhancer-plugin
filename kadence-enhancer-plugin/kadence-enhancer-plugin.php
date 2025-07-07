<?php
/**
 * Plugin Name: Kadence Enhancer Plugin
 * Description: A plugin to enhance the functionality of websites built with the Kadence theme. Includes YouTube carousel functionality.
 * Version: 1.1.0
 * Author: Gonzalo Gutierrez
 * Author URI: https://relaxedaxolotl.com
 * License: GPL2
 * Text Domain: kadence-enhancer-plugin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'KADENCE_ENHANCER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KADENCE_ENHANCER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// YouTube API constants (can be overridden in admin)
if (!defined('KEP_YT_API_KEY')) {
    define('KEP_YT_API_KEY', get_option('kep_youtube_api_key', ''));
}
if (!defined('KEP_YT_CHANNEL_ID')) {
    define('KEP_YT_CHANNEL_ID', get_option('kep_youtube_channel_id', ''));
}

// Include necessary files
require_once KADENCE_ENHANCER_PLUGIN_DIR . 'src/includes/core-functions.php';
require_once KADENCE_ENHANCER_PLUGIN_DIR . 'src/admin/admin-functions.php';
require_once KADENCE_ENHANCER_PLUGIN_DIR . 'src/public/public-functions.php';

// Activation hook
function kadence_enhancer_plugin_activate() {
    // Code to run on activation
}
register_activation_hook( __FILE__, 'kadence_enhancer_plugin_activate' );

// Deactivation hook
function kadence_enhancer_plugin_deactivate() {
    // Code to run on deactivation
}
register_deactivation_hook( __FILE__, 'kadence_enhancer_plugin_deactivate' );
?>