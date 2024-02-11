<?php

/**
 * Autolaoder
 *
 * This file is responsible for including all necessary files for the plugin.
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Autoloader {


	protected string $prefix = 'class-ipnfp';
	protected array $files   = array();

	public function __construct() {
		$this->register();
	}

	public function boot(): void {
		foreach ( $this->files as $file ) {
			$this->include_file( $file );
		}
	}

	protected function include_file( string $file ): void {
		if ( file_exists( realpath( $file ) ) ) {
			require_once realpath( $file );
		}
	}

	protected function register(): void {
		$files       = array();
		$directories = glob( IPNFP_INTL_PHONE_NUMBER_FORMAT_SRC_DIR . '/*', GLOB_ONLYDIR );
		foreach ( $directories as $directory ) {
			$files = array_merge(
				glob( $this->get_file_from_directory( $directory ) ),
				$files
			);
		}
		$this->files = $files;
	}

	protected function get_file_from_directory( string $directory ): string {
		return $directory . '/' . $this->prefix . '-*.php';
	}
}
