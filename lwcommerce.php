<?php

/**
 * @wordpress-plugin
 *
 * Plugin Name:       LWCommerce - BETA
 * Plugin URI:        https://lokuswp.id/plugin/lwcommerce
 * Description:       Sistem Jual Beli Online WordPress
 * Version:           0.5.0
 * Author:            LokusWP
 * Author URI:        https://lokuswp.id/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       lwcommerce
 * Domain Path:       /languages
 * Languages:         en_US
 */


// Checking Test Env and Direct Access File
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 * Define Constant
 */
defined( 'LOKUSWP_VERSION' ) or define( 'LOKUSWP_VERSION', '0.5.0' );
defined( 'LWC_VERSION' ) or define( 'LWC_VERSION', '0.5.0' );
defined( 'LWC_BASE' ) or define( 'LWC_BASE', plugin_basename( __FILE__ ) );
defined( 'LWC_PATH' ) or define( 'LWC_PATH', plugin_dir_path( __FILE__ ) );
defined( 'LWC_URL' ) or define( 'LWC_URL', plugin_dir_url( __FILE__ ) );
defined( 'LWC_STORAGE' ) or define( 'LWC_STORAGE', wp_get_upload_dir()['basedir'] . '/lwcommerce' );
defined( 'LWC_STRING_TEXT' ) or define( 'LWC_STRING_TEXT', '0.0.1' ); // String Version

// Autoload
require_once dirname( __DIR__ ) . '/lwcommerce/src/autoload.php';