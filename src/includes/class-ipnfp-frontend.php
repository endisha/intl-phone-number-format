<?php

/**
 * Frontend class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Frontend {


	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts(): void {
		if ( ! IPNFP_Global_Helper::should_enqueue_scripts() ) {
			return;
		}

		$resources = $this->get_resources();
		foreach ( $resources as $resource ) {
			$extension = pathinfo( $resource['source'], PATHINFO_EXTENSION );
			if ( $extension == 'js' ) {
				wp_enqueue_script( $resource['key'], $resource['source'], array( 'jquery' ), IPNFP_INTL_PHONE_NUMBER_FORMAT_VERSION, true );
			} elseif ( $extension == 'css' ) {
				wp_enqueue_style( $resource['key'], $resource['source'], array(), IPNFP_INTL_PHONE_NUMBER_FORMAT_VERSION );
			}
		}
		wp_add_inline_style( 'intl-phone-number-format', apply_filters( 'intl_phone_number_format_inline_css', '' ) );
		wp_localize_script( 'intl-phone-number-format', 'IntlPhoneNumberFormatData', $this->get_js_data() );
	}

	protected function get_resources(): array {
		return apply_filters(
			'intl_phone_number_format_enqueue_resources',
			array(
				array(
					'key'    => 'intl-tel-input',
					'source' => IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_JS_URL . '/intl-tel-input.min.js',
				),
				array(
					'key'    => 'intl-tel-input-utils',
					'source' => IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_JS_URL . '/utils.js',
				),
				array(
					'key'    => 'intl-phone-number-format',
					'source' => IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_JS_URL . '/intl-phone-number-format.js',
				),
				array(
					'key'    => 'intl-tel-input',
					'source' => IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_CSS_URL . '/intl-tel-input.min.css',
				),
				array(
					'key'    => 'intl-phone-number-format',
					'source' => IPNFP_INTL_PHONE_NUMBER_FORMAT_ASSETS_CSS_URL . '/style.css',
				),
			)
		);
	}

	protected function get_js_data(): array {
		$fields       = new IPNFP_Fields_Service();
		$woo_settings = new IPNFP_Woocommere_Service();

		return apply_filters(
			'intl_phone_number_format_enqueue_js_data',
			array(
				'base_country'       => $woo_settings->get_base_country(),
				'allowed_countries'  => $woo_settings->get_countries_for( 'billing' ),
				'shipping_countries' => $woo_settings->get_countries_for( 'shipping' ),
				'all_countries'      => $woo_settings->get_countries_for( 'all' ),
				'validations'        => IPNFP_Global_Helper::get_frontend_validation_meessages(),
				'patterns'           => ( new IPNFP_Validate_Service() )->custom_country_validations_pattern(),
				'lookup'             => IPNFP_Settings_Helper::get_lookup_settings_fields(),
				'fields'             => $fields->get_fields_for_frontend(),
				'input_helper_class' => apply_filters( 'intl_phone_number_format_enqueue_js_data_input_helper_class', 'woocommerce-help-text' ),
			)
		);
	}
}
