<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              frankluongo.com
 * @since             1.0.1
 * @package           Weather_Checker
 *
 * @wordpress-plugin
 * Plugin Name:       Weather Checker
 * Plugin URI:        frankluongo.com
 * Description:       This Plugin Checks the weather to determine if a shipping fee is required
 * Version:           1.0.3
 * Author:            Frank Luongo
 * Author URI:        frankluongo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weather-checker
 * Domain Path:       /languages
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WEATHER_CHECKER_VERSION', '1.1.0' );

function activate_weather_checker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weather-checker-activator.php';
	Weather_Checker_Activator::activate();
}

function deactivate_weather_checker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-weather-checker-deactivator.php';
	Weather_Checker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_weather_checker' );
register_deactivation_hook( __FILE__, 'deactivate_weather_checker' );

require plugin_dir_path( __FILE__ ) . 'includes/class-weather-checker.php';

function run_weather_checker() {

	$plugin = new Weather_Checker();
	$plugin->run();

}
run_weather_checker();
