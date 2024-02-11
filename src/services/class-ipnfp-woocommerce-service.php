<?php

/**
 * WooCommerce service class
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Woocommere_Service {

	public function get_country_codes( $type = 'all' ): array {
		$calling_codes = array();
		if ( $type === 'billing' ) {
			$countries = WC()->countries->get_allowed_countries();
		} elseif ( $type == 'shipping' ) {
			$countries = WC()->countries->get_shipping_countries();
		} else {
			$countries = WC()->countries->get_countries();
		}
		$countries = array_keys( $countries );
		foreach ( $countries as $prefix ) {
			$callingCode = WC()->countries->get_country_calling_code( $prefix );
			if ( ! empty( $callingCode ) ) {
				$calling_codes[] = $callingCode;
			}
		}
		return $calling_codes;
	}

	public function get_countries_for( $type = 'billing' ): array {
		if ( $type == 'billing' ) {
			$countries = WC()->countries->get_allowed_countries();
		} elseif ( $type == 'shipping' ) {
			$countries = WC()->countries->get_shipping_countries();
		} else {
			$countries = WC()->countries->get_countries();
		}
		return array_keys( $countries );
	}

	public function get_base_country(): string {
		$baseLocation = wc_get_base_location();
		return $baseLocation['country'];
	}

	public function is_woo_checkout_required_field( $key, $address_type = 'billing' ): ?bool {
		return WC()->checkout()->checkout_fields[ $address_type ][ $key ]['required'] ?? null;
	}
}
