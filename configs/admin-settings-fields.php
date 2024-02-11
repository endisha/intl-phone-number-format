<?php

/**
 * Admin settings fields config file
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

return array(
	array(
		'name' => __( 'International Phone Number Settings', 'intl-phone-number-format' ),
		'desc' => __( 'Set up international phone number configurations', 'intl-phone-number-format' ),
		'type' => 'title',
		'id'   => 'intl_phone_number_format_options',
	),
	array(
		'name' => __( 'Activate', 'intl-phone-number-format' ),
		'desc' => __( 'Enable or disable the formatting of phone numbers.', 'intl-phone-number-format' ),
		'type' => 'checkbox',
		'id'   => 'intl_phone_number_format_active',
	),
	array(
		'name'     => __( 'Lookup User\'s Country', 'intl-phone-number-format' ),
		'desc'     => __( 'Automatically set and initialize the selected country based on the user\'s IP address using the IP lookup service.', 'intl-phone-number-format' ),
		'type'     => 'checkbox',
		'id'       => 'intl_phone_number_format_lookup_active',
		'default'  => 'yes',
		'autoload' => false,
	),
	array(
		'name'              => __( 'Country Cache Expiry (hours)', 'intl-phone-number-format' ),
		'desc'              => __( 'Set the duration for caching the user\'s country.', 'intl-phone-number-format' ),
		'id'                => 'intl_phone_number_format_lookup_ttl',
		'type'              => 'number',
		'custom_attributes' => array(
			'min'  => 0,
			'step' => 1,
		),
		'css'               => 'width: 80px;',
		'default'           => '12',
		'autoload'          => false,
		'class'             => 'intl_phone_number_format_lookup_field',
		'autoload'          => false,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'intl_phone_number_format_options',
	),
);
