<?php

/**
 * Application
 *
 * @package IntlPhoneNumberFormat
 */

defined( 'ABSPATH' ) || exit;

class IPNFP_Application {


	/**
	 * Boot the plugin.
	 *
	 * @return void
	 */
	public function boot(): void {
		if ( $this->dependencies_enabled() ) {
			register_activation_hook( IPNFP_INTL_PHONE_NUMBER_FORMAT_FILE, array( $this, 'register_default_data' ) );
			add_action( 'activated_plugin', array( $this, 'activation' ) );
			add_filter( 'plugin_action_links', array( $this, 'plugin_action_settings' ), 10, 2 );
			$this->load_actions_filters();
			add_action( 'plugins_loaded', array( $this, 'load_i18n' ) );
		} else {
			$this->required_dependencies_notice();
		}
	}

	/**
	 * Load actions and filters for the plugin.
	 *
	 * @return void
	 */
	protected function load_actions_filters(): void {
		if ( $this->can_be_booted() ) {
			new IPNFP_Validations();
			new IPNFP_Frontend();
		}
		new IPNFP_Adminarea();
		new IPNFP_Global_Hooks();
	}

	/**
	 * Load internationalization (i18n) languages for the plugin.
	 *
	 * @return void
	 */
	public function load_i18n(): void {
		load_plugin_textdomain( 'intl-phone-number-format', false, IPNFP_INTL_PHONE_NUMBER_FORMAT_LANGUAGES_DIR );
	}

	/**
	 * Activation
	 *
	 * @param string $plugin
	 * @return void
	 */
	public function activation( string $plugin ): void {
		if ( $this->is_IPNFP_plugin( $plugin ) ) {
			wp_safe_redirect( $this->get_plugin_settings_url() );
			exit;
		}
	}

	/**
	 * Plugin action settings
	 *
	 * @param array  $links
	 * @param string $plugin
	 * @return array
	 */
	public function plugin_action_settings( array $links, string $plugin ): array {
		if ( $this->is_IPNFP_plugin( $plugin ) ) {
			$links[] = sprintf( '<a href="%s">%s</a>', $this->get_plugin_settings_url(), esc_html__( 'Settings', 'intl-phone-number-format' ) );
		}
		return $links;
	}

	/**
	 * Register default data for the plugin's settings.
	 *
	 * @return void
	 */
	public function register_default_data() {
		$options = array(
			'active'        => 'yes',
			'lookup_active' => 'yes',
			'lockup_ttl'    => 12,
		);
		foreach ( $options as $key => $value ) {
			$option = sprintf( 'ipnfp_intl_phone_number_format_%s', $key );
			if ( get_option( $option ) === false ) {
				update_option( $option, $value );
			}
		}
	}

	/**
	 * Show required dependencies notice
	 *
	 * @return void
	 */
	protected function required_dependencies_notice(): void {
		add_action(
			'admin_notices',
			function () {
				$plugin_name       = '<strong>' . __( 'International Phone Number Format', 'intl-phone-number-fromat' ) . '</strong>';
				$dependency_plugin = '<a href="https://wordpress.org/plugins/woocommerce" target="_blank">WooCommerce</a>';
				?>
			<div class="notice notice-error">
				<p>
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %1$s is the plugin name, %2$s is the dependency plugin name */
							__( '%1$s requires %2$s to be activated.', 'intl-phone-number-format' ),
							$plugin_name,
							$dependency_plugin
						),
						array(
							'a'      => array(
								'href'   => array(),
								'title'  => array(),
								'target' => array(),
							),
							'strong' => array(),
						)
					);
					?>
				</p>
			</div>
				<?php
			}
		);
	}

	/**
	 * Dependencies are enabled
	 *
	 * @return boolean
	 */
	protected function dependencies_enabled() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Can be booted?
	 *
	 * @return boolean
	 */
	protected function can_be_booted() {
		return get_option( 'ipnfp_intl_phone_number_format_active', 'no' ) == 'yes';
	}

	/**
	 * Get the plugin settings URL
	 *
	 * @return string
	 */
	protected function get_plugin_settings_url(): string {
		return admin_url(
			'admin.php?' . http_build_query(
				array(
					'page' => 'wc-settings',
					'tab'  => 'intl_phone_number_format',
				)
			)
		);
	}

	/**
	 * Check if the provided plugin is IPNF plugin
	 *
	 * @param string $plugin
	 * @return boolean
	 */
	protected function is_IPNFP_plugin( string $plugin ): bool {
		return $plugin == IPNFP_INTL_PHONE_NUMBER_FORMAT_PLUGIN_BASENAME;
	}
}
