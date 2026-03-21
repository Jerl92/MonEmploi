<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://Github.com/Jerl92/MonEmploi
 * @since             1.0.0
 * @package           Monemploi
 *
 * @wordpress-plugin
 * Plugin Name:       Monemploi 
 * Plugin URI:        https://https://Github.com/Jerl92/MonEmploi
 * Description:       MonEmploi is a job board to make a job searching web site with user dashboard and candidacy private page.
 * Version:           0.3.5
 * Author:            Jérémie Langevin 
 * Author URI:        https://https://Github.com/Jerl92/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       monemploi
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
define( 'MONEMPLOI_VERSION', '0.3.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-monemploi-activator.php
 */
function activate_monemploi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-monemploi-activator.php';
	Monemploi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-monemploi-deactivator.php
 */
function deactivate_monemploi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-monemploi-deactivator.php';
	Monemploi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_monemploi' );
register_deactivation_hook( __FILE__, 'deactivate_monemploi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-monemploi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_monemploi() {

	$plugin = new Monemploi();
	$plugin->run();

}
run_monemploi();
