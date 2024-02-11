<?php

/**
 * Fields service class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Fields_Service {

	protected array $fields = array();

	public function __construct() {
		$fields = apply_filters( 'intl_phone_number_format_fields', array() );

		$this->fields = $fields ?? array();
	}

	public function get_fields_for_frontend(): array {
		return array_values(
			array_filter(
				array_map(
					function ( $field ) {
						if ( $field['enable'] ?? false ) {
							return array(
								'id'        => $field['id'],
								'required'  => $field['frontend_validation'],
								'countries' => $field['countries'],
								'type'      => $field['type'],
							);
						}
						return array();
					},
					$this->fields
				)
			)
		);
	}

	public function get_fields(): array {
		return array_values(
			array_filter(
				$this->fields,
				function ( $field ) {
					return $field['enable'];
				}
			)
		);
	}

	public function get_shipping_fields(): array {
		$fields = array_column(
			array_filter(
				$this->fields,
				function ( $field ) {
					return $field['enable'];
				}
			),
			'type',
			'id'
		);

		return array_keys(
			array_filter(
				$fields,
				function ( $value ) {
					return $value === 'shipping';
				}
			)
		);
	}
}
