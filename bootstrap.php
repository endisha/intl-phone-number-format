<?php

/**
 * Bootstrap file
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

require_once IPNFP_INTL_PHONE_NUMBER_FORMAT_AUTOLOADER;

$autoloader = new IPNFP_Autoloader();
$autoloader->boot();

$app = new IPNFP_Application();
$app->boot();
