<?php

/**
 * Global hooks class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Global_Hooks {


	public function __construct() {
		add_filter( 'intl_phone_number_format_fields', array( $this, 'include_fields' ), 1000, 1 );
	}

	public function include_fields( array $fields ): array {
		$options = get_option( 'ipnfp_intl_phone_number_format_keys', array() );
		$fields  = array_merge( IPNFP_Settings_Helper::get_frontend_fields(), $fields );

		foreach ( $fields as $index => $field ) {
			if ( isset( $options[ $field['id'] ] ) ) {
				$fields[ $index ] = array_merge( $field, $options[ $field['id'] ] );
			}
		}

		foreach ( $fields as $index => $field ) {
			$key                                     = $field['id'] ?? false;
			$fields[ $index ]['id']                  = apply_filters( "intl_phone_number_format_modify_{$key}_id", $field['id'], $field );
			$fields[ $index ]['enable']              = apply_filters( "intl_phone_number_format_modify_{$key}_enable", $field['enable'], $field );
			$fields[ $index ]['label']               = apply_filters( "intl_phone_number_format_modify_{$key}_label", $field['label'] ?? '', $field );
			$fields[ $index ]['frontend_validation'] = apply_filters( "intl_phone_number_format_modify_{$key}_frontend_validation", $field['frontend_validation'] ?? false, $field );
			$fields[ $index ]['backend_validation']  = apply_filters( "intl_phone_number_format_modify_{$key}_backend_validation", $field['backend_validation'] ?? false, $field );
			$fields[ $index ]['countries']           = apply_filters( "intl_phone_number_format_modify_{$key}_countries", $field['countries'] ?? 'billing', $field );
			$fields[ $index ]['type']                = apply_filters( "intl_phone_number_format_modify_{$key}_type", $field['type'] ?? 'billing', $field );
		}

		return $fields;
	}
}
