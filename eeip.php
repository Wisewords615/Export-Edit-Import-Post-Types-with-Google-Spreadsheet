<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://veebiehitus.ee
 * @since             1.0.0
 * @package           Eeip
 *
 * @wordpress-plugin
 * Plugin Name:       Export/Edit/Import Post-types with Google spreadsheet
 * Plugin URI:        http://veebiehitus.ee/EEI
 * Description:       This plugin can be used to mass-edit post type metadata
 * Version:           1.0.0
 * Author:            Martti Randma
 * Author URI:        http://veebiehitus.ee
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eeip
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eeip-activator.php
 */
function activate_eeip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eeip-activator.php';
	Eeip_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eeip-deactivator.php
 */
function deactivate_eeip() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eeip-deactivator.php';
	Eeip_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eeip' );
register_deactivation_hook( __FILE__, 'deactivate_eeip' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-eeip.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_eeip() {

	$plugin = new Eeip();
	$plugin->run();

}
run_eeip();
