<?php

/**
 * Admin settings service class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Admin_Settings_Service {

	public function get_fields( array $settings ): void {
		woocommerce_admin_fields( $settings );
	}

	public function get_additional_fields( array $fields ): void {
		$values = get_option( 'ipnfp_intl_phone_number_format_keys', array() );
		$fields = $this->prepare_additional_fields( $fields, $values );
		IPNFP_Global_Helper::get_view(
			'admin-additional-fields',
			array(
				'fields' => $fields,
				'values' => $values,
			)
		);
	}

	public function update_fields( array $settings ): void {
		woocommerce_update_options( $settings );
	}

	public function update_additional_fields( $fields ): void {
		$options = array();
		foreach ( $fields as $field ) {
			$id = sanitize_key( $field['id'] ) ?? null;
			if ( $id && isset( $_POST['options'][ $id ] ) ) {
				$value          = array_map( 'sanitize_text_field', $_POST['options'][ $id ] ?? array() );
				$options[ $id ] = array(
					'enable'              => intval( $value['enable'] ?? 0 ),
					'frontend_validation' => intval( $value['frontend_validation'] ?? 0 ),
					'backend_validation'  => intval( $value['backend_validation'] ?? 0 ),
					'countries'           => sanitize_key( $value['countries'] ?? '' ) ?? '',
					'type'                => sanitize_key( $value['type'] ?? '' ) ?? '',
				);
			}
		}
		update_option( 'ipnfp_intl_phone_number_format_keys', $options );
	}

	protected function prepare_additional_fields( array $additional_fields, array $values ): array {
		$fields = array();
		foreach ( $additional_fields as $field ) {
			$id                  = $field['id'];
			$label               = $field['label'] ?? '';
			$desc                = $field['desc'] ?? '';
			$enable              = isset( $values[ $id ]['enable'] ) ? wp_validate_boolean( $values[ $id ]['enable'] ) : wp_validate_boolean( $field['enable'] );
			$frontend_validation = isset( $values[ $id ]['frontend_validation'] ) ? wp_validate_boolean( $values[ $id ]['frontend_validation'] ) : wp_validate_boolean( $field['frontend_validation'] );
			$backend_validation  = isset( $values[ $id ]['backend_validation'] ) ? wp_validate_boolean( $values[ $id ]['backend_validation'] ) : wp_validate_boolean( $field['backend_validation'] );
			$countries           = isset( $values[ $id ]['countries'] ) ? $values[ $id ]['countries'] : $field['countries'];
			$type                = isset( $values[ $id ]['type'] ) ? $values[ $id ]['type'] : $field['type'];

			$wc_field = false;
			if ( isset( WC()->checkout()->checkout_fields[ $type ][ $id ] ) ) {
				$wc_field          = true;
				$required_wc_field = WC()->checkout()->checkout_fields[ $type ][ $id ]['required'] ?? false;
			}

			$fields[] = array(
				'id'                  => $id,
				'enable'              => $enable,
				'label'               => $label,
				'desc'                => $desc,
				'frontend_validation' => $frontend_validation,
				'backend_validation'  => $backend_validation,
				'countries'           => $countries,
				'type'                => $type,
				'is_wc_field'         => $wc_field,
				'wc_field_required'   => $wc_field ? $required_wc_field : false,
			);
		}
		return $fields;
	}
}
