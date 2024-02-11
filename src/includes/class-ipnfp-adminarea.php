<?php

/**
 * Adminarea class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Adminarea {


	protected array $fields = array();

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'include_plugin_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_intl_phone_number_format', array( $this, 'get_fields' ) );
		add_action( 'woocommerce_update_options_intl_phone_number_format', array( $this, 'update_fields' ) );
	}

	public function enqueue_scripts(): void {
		if ( IPNFP_Global_Helper::is_plugin_settings_page() ) {
			wp_enqueue_script( 'intl-phone-number-format-plugin-settings', IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_JS_URL . '/plugin-settings.js', array( 'jquery' ), IPNFP_INTL_PHONE_NUMBER_FORMAT_VERSION, true );
			wp_enqueue_style( 'intl-phone-number-format-plugin-settings', IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_CSS_URL . '/plugin-settings.css', array(), IPNFP_INTL_PHONE_NUMBER_FORMAT_VERSION );
		}
	}

	public function include_plugin_tab( array $tabs ): array {
		$tabs['intl_phone_number_format'] = __( 'International Phone Number Format', 'intl-phone-number-format' );
		return $tabs;
	}

	public function get_fields(): void {
		$fields            = apply_filters( 'intl_phone_number_format_admin_settings_fields', IPNFP_Global_Helper::get_config_file( 'admin-settings-fields' ) );
		$additional_fields = apply_filters( 'intl_phone_number_format_fields', array() );

		$settings = new IPNFP_Admin_Settings_Service();
		$settings->get_fields( $fields );
		$settings->get_additional_fields( $additional_fields );
	}

	public function update_fields(): void {
		$fields            = apply_filters( 'intl_phone_number_format_admin_settings_fields', IPNFP_Global_Helper::get_config_file( 'admin-settings-fields' ) );
		$additional_fields = apply_filters( 'intl_phone_number_format_fields', array() );

		$settings = new IPNFP_Admin_Settings_Service();
		$settings->update_fields( $fields );
		$settings->update_additional_fields( $additional_fields );
	}
}
