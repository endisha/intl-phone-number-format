<?php

/**
 * Additional fields template for admin settings page
 *
 * @package IntlPhoneNumberFormat
 */

defined('ABSPATH') || exit;
?>

<h2 class="additional-fields-haed">
	<?php esc_html_e('Fields', 'intl-phone-number-format'); ?>
</h2>
<div class="additional-fields-desc">
	<p>
		<?php esc_html_e('Configure and set up formatted fields for applying an international phone number format.', 'intl-phone-number-format'); ?>
	</p>
</div>
<table class="form-table additional-fields widefat" aria-describedby="additional-fields-table">
	<thead>
		<tr>
			<th scope="col">
				<?php esc_html_e('Field Label', 'intl-phone-number-format'); ?>
			</th>
			<th scope="col">
				<?php esc_html_e('Enable', 'intl-phone-number-format'); ?>
			</th>
			<th scope="col">
				<?php esc_html_e('Frontend Validation', 'intl-phone-number-format'); ?>
				<?php echo wp_kses_post(wc_help_tip(esc_html__('Allows validation of the field in the frontend using the browser', 'intl-phone-number-format'))); ?></span>
			</th>
			<th scope="col">
				<?php esc_html_e('Backend Validation', 'intl-phone-number-format'); ?>
				<?php echo wp_kses_post(wc_help_tip(esc_html__('Allows validation of the field in the backend on the server side', 'intl-phone-number-format'))); ?></span>
			</th>
			<th scope="col">
				<?php esc_html_e('View Countries From', 'intl-phone-number-format'); ?>
				<?php echo wp_kses_post(wc_help_tip(esc_html__('Show countries based on your WooCommerce settings', 'intl-phone-number-format'))); ?></span>
			</th>
			<th scope="col">
				<?php esc_html_e('Field Type', 'intl-phone-number-format'); ?>
				<?php echo wp_kses_post(wc_help_tip(esc_html__('The field type depends on where the field will appear and be associated with it', 'intl-phone-number-format'))); ?></span>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($fields)) : ?>
			<?php foreach ($fields as $field) : ?>
				<tr>
					<th scope="row">
						<span class="label">
							<?php echo esc_html($field['label']); ?>
							<?php if (in_array($field['id'], array('billing_phone', 'shipping_phone')) && !$field['is_wc_field']) : ?>
								<span class="wc-field">
									|
									<span class="wc-not-exist">
										<?php esc_html_e('Does Not Exist', 'intl-phone-number-format'); ?>
										<?php echo wp_kses_post(wc_help_tip(esc_html__('This field does not exist in WooCommerce checkout fields.', 'intl-phone-number-format'))); ?>
									</span>
								</span>
							<?php elseif ($field['is_wc_field']) : ?>
								<span class="wc-field">
									|
									<?php if ($field['wc_field_required']) : ?>
										<span class="wc-required">
											<?php esc_html_e('Required', 'intl-phone-number-format'); ?>
											<?php echo wp_kses_post(wc_help_tip(esc_html__('This is a required WooCommerce checkout field.', 'intl-phone-number-format'))); ?>
										</span>
									<?php else : ?>
										<span class="wc-optional">
											<?php esc_html_e('Optional', 'intl-phone-number-format'); ?>
											<?php echo wp_kses_post(wc_help_tip(esc_html__('This is an optional WooCommerce checkout field.', 'intl-phone-number-format'))); ?>
										</span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
						</span>
						<span class="key">
							<?php echo esc_html($field['id']); ?>
						</span>
						<span class="desc">
							<?php echo esc_html($field['desc']); ?>
						</span>

					</th>
					<td>
						<?php
						woocommerce_form_field(
							sprintf(
								'options[%s][enable]',
								sanitize_key($field['id'])
							),
							array(
								'type' => 'checkbox',
							),
							wp_validate_boolean($field['enable'])
						);
						?>
					</td>
					<td>
						<?php
						woocommerce_form_field(
							sprintf(
								'options[%s][frontend_validation]',
								sanitize_key($field['id'])
							),
							array(
								'type' => 'checkbox',
							),
							wp_validate_boolean($field['frontend_validation'])
						);
						?>
					</td>
					<td>
						<?php
						woocommerce_form_field(
							sprintf(
								'options[%s][backend_validation]',
								sanitize_key($field['id'])
							),
							array(
								'type' => 'checkbox',
							),
							wp_validate_boolean($field['backend_validation'])
						);
						?>
					</td>
					<td>
						<?php
						woocommerce_form_field(
							sprintf(
								'options[%s][countries]',
								sanitize_key($field['id'])
							),
							array(
								'type'    => 'select',
								'options' => array(
									'all'      => __('All Countries', 'intl-phone-number-format'),
									'billing'  => __('Billing Countries', 'intl-phone-number-format'),
									'shipping' => __('Shipping Countries', 'intl-phone-number-format'),
								),
							),
							sanitize_key($field['countries'])
						);
						?>
					</td>
					<td>
						<?php
						woocommerce_form_field(
							sprintf('options[%s][type]', sanitize_key($field['id'])),
							array(
								'type'    => 'select',
								'options' => array(
									'custom'   => __('Custom', 'intl-phone-number-format'),
											'billing'  => __('Billing', 'intl-phone-number-format'),
									'shipping' => __('Shipping', 'intl-phone-number-format'),
								),
							),
							sanitize_key($field['type'])
						);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td colspan="6">
					<?php esc_html_e('No Fields', 'intl-phone-number-format'); ?>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>