<?php

/**
 * Settings helper class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Settings_Helper {


	/**
	 * Get frontend fields
	 *
	 * @return array
	 */
	public static function get_frontend_fields(): array {
		$fields = IPNFP_Global_Helper::get_config_file( 'frontend-fields' );
		return apply_filters( 'intl_phone_number_format_get_fields', $fields );
	}

	/**
	 * Get lookup settings fields
	 *
	 * @return array
	 */
	public static function get_lookup_settings_fields(): array {
		$active = wp_validate_boolean( get_option( 'ipnfp_intl_phone_number_format_lookup_active', 'no' ) == 'yes' );
		$ttl    = intval( get_option( 'ipnfp_intl_phone_number_format_lookup_ttl', 12 ) );

		if ( $ttl <= 0 ) {
			$active = false;
		}

		return array(
			'active' => $active,
			'ttl'    => $ttl,
		);
	}
}
