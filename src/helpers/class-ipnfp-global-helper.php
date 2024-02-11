<?php

/**
 * Global helper class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Global_Helper {


	/**
	 * Get frontend validation messages
	 *
	 * @return array
	 */
	public static function get_frontend_validation_meessages(): array {
		return apply_filters(
			'intl_phone_number_format_js_validation_messages',
			array(
				'invalid_phone_number_format' => __( 'The field must be a valid phone number', 'intl-phone-number-format' ),
				'required_phone_number'       => __( 'The field is required', 'intl-phone-number-format' ),
			)
		);
	}

	/**
	 * Should enqueue scripts CSS and JS
	 *
	 * @return boolean
	 */
	public static function should_enqueue_scripts(): bool {
		$valid = false;
		if ( is_checkout() || is_account_page() ) {
			$valid = true;
		} elseif ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen ) {
				$valid = in_array( $screen->base, array( 'profile', 'user-edit' ) ) ||
					$screen->base === 'post' && $screen->post_type === 'shop_order';
			}
		}
		return apply_filters( 'intl_phone_number_format_validate_enqueue_js', $valid );
	}

	/**
	 * Is plugin settings page
	 *
	 * @return boolean
	 */
	public static function is_plugin_settings_page(): bool {
		$valid = false;
		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen ) {
				$valid = in_array( $screen->base, array( 'woocommerce_page_wc-settings' ) ) &&
					isset( $_GET['tab'] ) && sanitize_key( $_GET['tab'] ) === 'intl_phone_number_format';
			}
		}
		return $valid;
	}

	/**
	 * Get config file
	 *
	 * @param string $config
	 * @return array
	 */
	public static function get_config_file( string $config ): array {
		$configs = array();
		$file    = IPNFP_INTL_PHONE_NUMBER_FORMAT_CONFIG_DIR . '/' . $config . '.php';
		if ( file_exists( realpath( $file ) ) ) {
			$configs = require realpath( $file );
		}
		return $configs;
	}

	/**
	 * Get view template file
	 *
	 * @param string $template
	 * @param array  $data
	 * @return void
	 */
	public static function get_view( string $template, array $data = array() ): void {
		$file = IPNFP_INTL_PHONE_NUMBER_FORMAT_VIEWS_DIR . '/' . $template . '.php';
		if ( file_exists( realpath( $file ) ) ) {
			extract( $data );
			require realpath( $file );
		}
	}
}
