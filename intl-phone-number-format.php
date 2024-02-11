<?php

/**
 * Plugin Name: International Phone Number Format
 * Plugin URI: https://endisha.ly/
 * Description: International phone number formats for WooCommerce.
 * Author: Mohamed Endisha
 * Author URI: https://endisha.ly
 * Version: 1.0.0
 * Text Domain: intl-phone-number-format
 * Domain Path: /src/languages/
 * Requires at least: 6.0
 * Requires PHP: 8.0
 *
 * @package IntlPhoneNumberFormat
 * @version 1.0.0
 * @author Mohamed Endisha
 * @copyright Copyright (c) 2023.
 */

defined( 'ABSPATH' ) || exit;

// Define constants
if ( ! defined( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_DIR' ) ) {
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_VERSION', '1.0.0' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_FILE', __FILE__ );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_DIR', __DIR__ );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_DIR_BASENAME', basename( __DIR__ ) );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_FILE_BASENAME', basename( __FILE__ ) );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_PLUGIN_BASENAME', basename( __DIR__ ) . '/' . basename( __FILE__ ) );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_SRC_DIR', __DIR__ . '/src' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_CONFIG_DIR', __DIR__ . '/configs' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_LANGUAGES_DIR', basename( __DIR__ ) . '/src/languages/' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_VIEWS_DIR', __DIR__ . '/src/views' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_AUTOLOADER', __DIR__ . '/src/core/class-ipnfp-autoloader.php' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_CSS_URL', IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_URL . '/css' );
	define( 'IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_JS_URL', IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_URL . '/js' );
}

require_once __DIR__ . '/bootstrap.php';
