<?php

/**
 * Validate service class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Validate_Service {


	public function validate( array $fields, ?array $data = null ): array {
		$errors = array();

		foreach ( $fields as $field ) {

			$id = $field['id'] ?? '';

			if ( ! $id ) {
				continue;
			}

			$value = '';
			if ( ! is_null( $data ) && isset( $data[ $id ] ) ) {
				$value                     = sanitize_text_field( $data[ $id ] );
				$ship_to_different_address = ! isset( $data['ship_to_different_address'] ) || ! wp_validate_boolean( $data['ship_to_different_address'] );
			} elseif ( isset( $_POST[ $id ] ) ) {
				$value                     = sanitize_text_field( $_POST[ $id ] );
				$ship_to_different_address = ! isset( $_POST['ship_to_different_address'] ) || ! wp_validate_boolean( $_POST['ship_to_different_address'] );
			} else {
				continue;
			}

			$required = boolval( $field['backend_validation'] );
			$type     = sanitize_text_field( $field['type'] );
			$label    = sanitize_text_field( $field['label'] );

			if ( ! $required ) {
				continue;
			}

			$should_be_validated   = $this->should_be_validated( $id, $ship_to_different_address );
			$validate_phone_number = $this->validate_phone_number_format( $value, $type );

			$should_be_validated   = apply_filters( 'intl_phone_number_format_should_be_validate_field', $should_be_validated, $id, $type );
			$validate_phone_number = apply_filters( 'intl_phone_number_format_validate_phone_number', $validate_phone_number, $value, $id, $type );

			if ( $should_be_validated && ! $validate_phone_number ) {
				$errors[ $id ] = wp_kses(
					/* translators: %1$s is a placeholder for a field name */
					sprintf( __( '<strong>%1$s</strong> format is invalid', 'intl-phone-number-format' ), $label ),
					array( 'strong' => array() )
				);
			}
		}

		return $errors;
	}

	protected function should_be_validated( $field, $ship_to_different_address ): bool {
		$fields          = ( new IPNFP_Fields_Service() )->get_shipping_fields();
		$shipping_fields = apply_filters( 'intl_phone_number_format_validated_inputs', $fields );
		$validate        = ! ( in_array( $field, $shipping_fields ) && $ship_to_different_address );
		return apply_filters( 'intl_phone_number_format_should_be_validated_field_option', $validate, $field );
	}

	protected function validate_phone_number_format( $phone_number, $countries_type = 'billing' ): bool {
		$phone_number = preg_replace( '/[^+\d]/', '', $phone_number );

		$prefixes = $this->get_country_calling_codes( $countries_type );

		$prefix = $this->get_phone_number_prefix( $prefixes, $phone_number );

		$validate = $this->is_valid_phone_number( $phone_number, $prefix, $prefixes );

		return apply_filters( 'intl_phone_number_format_valid_phone_number_format', $validate, $prefix, $phone_number, $prefixes );
	}

	protected function is_valid_phone_number( $phone_number, $prefix, $prefixes ): bool {
		$pattern  = '/^(' . implode( '|', $prefixes ) . ')\d{4,15}$/';
		$validate = preg_match( $pattern, $phone_number );

		$custom_validation_pattern = $this->custom_country_prefix_validation_pattern( $prefix );
		if ( $custom_validation_pattern ) {
			$validate = preg_match( '/' . $custom_validation_pattern . '/i', $phone_number );
		}
		return $validate;
	}

	protected function get_phone_number_prefix( $prefixes, $phone_number ): string {
		$matched_prefix = '';
		foreach ( $prefixes as $prefix ) {
			$pattern = '/' . '^' . $prefix . '/is'; // Add delimiters here
			if ( preg_match( $pattern, $phone_number, $matches ) ) {
				$matched_prefix = $matches[0];
				break;
			}
		}
		return $matched_prefix;
	}

	protected function get_country_calling_codes( $countries_type ): array {
		$prefixes = ( new IPNFP_Woocommere_Service() )->get_country_codes( $countries_type );
		return array_map(
			function ( $prefix ) {
				return '\\' . $prefix;
			},
			$prefixes
		);
	}

	public function custom_country_prefix_validation_pattern( $prefix = '' ): string {
		$patterns = $this->custom_country_validations_pattern();
		return $patterns[ $prefix ] ?? '';
	}

	public function custom_country_validations_pattern(): ?array {
		return apply_filters( 'intl_phone_number_format_custom_country_prefixes_validation', array() );
	}
}
