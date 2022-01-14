<?php

/**
 * @link              https://wordpress.org
 * @since             1.0.0
 * @package           Wordroid
 *
 * @wordpress-plugin
 * Plugin Name:       Word-APP
 * Plugin URI:        https://wordpress.org
 * Description:       Plugin de Integração com o Aplicativo Personalizado. 
 * Version:           2.0.0
 * Author:            Word-APP
 * Author URI:        https://wordpress.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordroid4
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WORDROID4_VERSION', '2.0.0' );
define('APP_NAME','H(q|EQL<pe|Nm+u?<`+]G!%x}+UMT.[a>lk;tRo-4+x5]-,tzFM}igv)]&)#K<-S');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordroid4-activator.php
 */
function activate_wordroid4() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordroid4-activator.php';
	Wordroid4_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordroid4-deactivator.php
 */
function deactivate_wordroid4() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordroid4-deactivator.php';
	Wordroid4_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordroid4' );
register_deactivation_hook( __FILE__, 'deactivate_wordroid4' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once __DIR__ . '/cmb2/init.php';
require_once __DIR__ . '/admin/class-send-notification.php';
require plugin_dir_path( __FILE__ ) . '/customized-api.php';
require plugin_dir_path( __FILE__ ) . '/new_api.php';
require_once __DIR__ . '/category_api.php';
require_once __DIR__ . '/home_api.php';
require_once __DIR__ . '/post_api.php';
//Custom Fields
require plugin_dir_path( __FILE__ ) . 'fields/post-search-ajax.php';
require plugin_dir_path( __FILE__ ) . 'fields/cmb-field-select2.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wordroid4.php';

add_action( 'transition_post_status', 'post_transition_action_wordroid4', 10, 3 );
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordroid4() {

	$plugin = new Wordroid4();
	$plugin->run();

}
run_wordroid4();
