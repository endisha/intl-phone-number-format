<?php

/**
 * Validations class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Validations {


	public function __construct() {
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout_fields' ), 10, 2 );
		add_action( 'woocommerce_after_save_address_validation', array( $this, 'validate_addresses_fields_in_forntend_profile' ), 10 );
		add_action( 'user_profile_update_errors', array( $this, 'validate_checkout_fields_in_admin_user_profile' ), 10 );
		add_action( 'pre_post_update', array( $this, 'validate_checkout_fields_in_admin_order' ), 10 );
	}

	public function validate_checkout_fields( array $data, WP_Error $errors ) {
		$fields    = ( new IPNFP_Fields_Service() )->get_fields();
		$validated = ( new IPNFP_Validate_Service() )->validate( $fields, $data );

		if ( ! empty( $validated ) ) {
			foreach ( $validated as $error ) {
				$errors->add( 'validation', $error );
			}
		}
	}

	public function validate_checkout_fields_in_admin_order( int $post_id ) {
		if ( get_post_type( $post_id ) !== 'shop_order' ) {
			return;
		}

		$fields    = ( new IPNFP_Fields_Service() )->get_fields();
		$validated = ( new IPNFP_Validate_Service() )->validate( $fields );

		$errors = array_values( $validated );
		if ( ! empty( $errors ) ) {
			$error_message = implode( '<br />', $errors );
			wp_die( wp_kses( $error_message, array( 'br' => array() ) ) );
		}
	}

	public function validate_addresses_fields_in_forntend_profile() {
		$fields    = ( new IPNFP_Fields_Service() )->get_fields();
		$validated = ( new IPNFP_Validate_Service() )->validate( $fields );

		if ( ! empty( $validated ) ) {
			foreach ( $validated as $error ) {
				wc_add_notice( $error, 'error' );
			}
		}
	}

	public function validate_checkout_fields_in_admin_user_profile( WP_Error $errors ) {
		$fields    = ( new IPNFP_Fields_Service() )->get_fields();
		$validated = ( new IPNFP_Validate_Service() )->validate( $fields );

		if ( ! empty( $validated ) ) {
			foreach ( $validated as $field => $error ) {
				$errors->add( $field, $error );
			}
		}
		return $errors;
	}
}
