<?php

/**
 * Frontend fields config file
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'id'                  => 'billing_phone',
		'enable'              => true,
		'label'               => __( 'Billing Phone', 'intl-phone-number-format' ),
		'desc'                => __( 'This is the billing phone number on the frontend checkout form and the edit billing address form.', 'intl-phone-number-format' ),
		'frontend_validation' => true,
		'backend_validation'  => true,
		'countries'           => 'billing',
		'type'                => 'billing',
	),
	array(
		'id'                  => 'shipping_phone',
		'enable'              => true,
		'label'               => __( 'Shipping Phone', 'intl-phone-number-format' ),
		'desc'                => __( 'This is the shipping phone number on the frontend checkout form and the edit shipping address form.', 'intl-phone-number-format' ),
		'frontend_validation' => true,
		'backend_validation'  => true,
		'countries'           => 'shipping',
		'type'                => 'shipping',
	),
	array(
		'id'                  => '_billing_phone',
		'enable'              => true,
		'label'               => __( 'Billing Phone', 'intl-phone-number-format' ),
		'desc'                => __( 'This is the billing phone number field for the backend edit order page.', 'intl-phone-number-format' ),
		'frontend_validation' => true,
		'backend_validation'  => true,
		'countries'           => 'billing',
		'type'                => 'billing',
	),
	array(
		'id'                  => '_shipping_phone',
		'enable'              => true,
		'label'               => __( 'Shipping Phone', 'intl-phone-number-format' ),
		'desc'                => __( 'This is the shipping phone number field for the backend edit order page.', 'intl-phone-number-format' ),
		'frontend_validation' => true,
		'backend_validation'  => true,
		'countries'           => 'shipping',
		'type'                => 'shipping',
	),
);
