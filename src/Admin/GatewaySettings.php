<?php

namespace Simplixi\SUCheckout\UPayments\Admin;

/**
 * Gateway settings schema, validation and admin presentation adapter.
 *
 * Runtime payment orchestration deliberately remains outside this class. The
 * multi-merchant surface represented here is the inherited single additional
 * merchant allocation only; it does not authorize arbitrary split routing.
 */
final class GatewaySettings {
    /**
     * Build the inherited WooCommerce gateway field schema.
     *
     * @param string $domain Gateway text domain.
     * @param string $method_title Gateway method title.
     * @param string $method_description Gateway method description.
     * @return array
     */
    public static function fields($domain, $method_title, $method_description) {
        return array(
            'enabled' => array(
                'title' => __('Active', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => ' ',
                'default' => 'yes',
            ),
            'make_default_gateway' => array(
                'title' => __('Default Gateway', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Make UPayments the default payment method at checkout', 'sucheckout-upayments'),
                'default' => 'no',
                'description' => __('If enabled, UPayments will be preselected at checkout. Merchants can still reorder gateways.', 'sucheckout-upayments'),
            ),
            'title' => array(
                'title' => __('Title', 'sucheckout-upayments'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'sucheckout-upayments'),
                'default' => $method_title,
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'sucheckout-upayments'),
                'type' => 'textarea',
                'description' => __('Instructions that the customer will see on your checkout.', 'sucheckout-upayments'),
                'default' => $method_description,
                'desc_tip' => true,
            ),
            'api_key' => array(
                'title' => __('Api Key', 'sucheckout-upayments'),
                'type' => 'text',
                'description' => __('Copy/paste values from UPayments dashboard', 'sucheckout-upayments'),
                'default' => '',
                'desc_tip' => true,
            ),
            'debug' => array(
                'title' => __('Debug logging', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Log non-sensitive UPayments diagnostic events to WooCommerce logs.', 'sucheckout-upayments'),
                'default' => 'no',
            ),
            'test_mode' => array(
                'title' => __('Test Mode', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => ' ',
                'default' => 'no',
            ),
            'is_order_complete' => array(
                'title' => __('Show paid orders as "Completed"?', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => ' ',
                'default' => 'yes',
            ),
            'save_card_section_title' => array(
                'title' => __('Card Tokenization & Design', 'sucheckout-upayments'),
                'type' => 'title',
                'description' => '',
            ),
            'use_new_design' => array(
                'title' => __('Use New Design', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Use the modern design (if unchecked uses classic design)', 'sucheckout-upayments'),
                'default' => 'yes',
            ),
            'enable_save_card' => array(
                'title' => __('Enable Save Card', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Allow customers to save card details (Tokenization)', 'sucheckout-upayments'),
                'default' => 'yes',
            ),
            'multimerchant_section_title' => array(
                'title' => __('Multimerchant Configuration', 'sucheckout-upayments'),
                'type' => 'title',
            ),
            'enable_multimerchant' => array(
                'title' => __('Enable Multimerchant', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Handle Merchant Account & Charges', 'sucheckout-upayments'),
                'default' => 'no',
            ),
            'iban_number' => array(
                'type' => 'text',
                'css' => 'display:none;',
            ),
            'cc_charge' => array(
                'type' => 'text',
                'css' => 'display:none;',
            ),
            'cc_charge_type' => array(
                'type' => 'text',
                'css' => 'display:none;',
            ),
            'knet_charge' => array(
                'type' => 'text',
                'css' => 'display:none;',
            ),
            'knet_charge_type' => array(
                'type' => 'text',
                'css' => 'display:none;',
            ),
            'multimerchant_accounts' => array(
                'title' => __('Multimerchant Accounts', 'sucheckout-upayments'),
                'type' => 'multimerchant_repeater',
                'description' => __('Manage IBAN and charges for Main-Merchant.', 'sucheckout-upayments'),
            ),
            'autodeduction_section_title' => array(
                'title' => __('Subscription Configuration', 'sucheckout-upayments'),
                'type' => 'title',
            ),
            'enable_subscriptions' => array(
                'title' => __('Enable Subscriptions', 'sucheckout-upayments'),
                'type' => 'checkbox',
                'label' => __('Enable subscription payments', 'sucheckout-upayments'),
                'default' => 'no',
                'desc_tip' => true,
                'description' => __('Only Subscription Products are allowed at checkout If Subscription is enabled.', 'sucheckout-upayments'),
            ),
        );
    }

    /**
     * Preserve the inherited subscription/save-card dependency.
     *
     * @param array $settings Sanitized WooCommerce settings.
     * @return array{settings: array, forced_save_card: bool}
     */
    public static function normalize_dependencies(array $settings) {
        $save_card = isset($settings['enable_save_card']) && !empty($settings['enable_save_card']);
        $subscriptions = isset($settings['enable_subscriptions']) && !empty($settings['enable_subscriptions']);
        $forced = $subscriptions && !$save_card;
        if ($forced) {
            $settings['enable_save_card'] = 'yes';
        }
        return array('settings' => $settings, 'forced_save_card' => $forced);
    }

    /**
     * Validate/normalize the gateway post data before Woo field processing.
     *
     * @param array $post_data WooCommerce gateway post data.
     * @return array{post_data: array, api_key_missing: bool, multimerchant_missing: bool}
     */
    public static function prepare_post_data(array $post_data) {
        $api_key_missing = empty($post_data['woocommerce_upayments_api_key']);
        $multimerchant_missing = false;

        if (!$api_key_missing) {
            if (isset($post_data['woocommerce_upayments_enable_multimerchant'])
                && $post_data['woocommerce_upayments_enable_multimerchant'] == 1
            ) {
                $required = array(
                    'woocommerce_upayments_iban_number',
                    'woocommerce_upayments_cc_charge',
                    'woocommerce_upayments_cc_charge_type',
                    'woocommerce_upayments_knet_charge',
                    'woocommerce_upayments_knet_charge_type',
                );
                foreach ($required as $key) {
                    if (empty($post_data[$key])) {
                        $multimerchant_missing = true;
                        break;
                    }
                }
            } else {
                $post_data['woocommerce_upayments_iban_number'] = null;
                $post_data['woocommerce_upayments_cc_charge'] = null;
                $post_data['woocommerce_upayments_cc_charge_type'] = null;
                $post_data['woocommerce_upayments_knet_charge'] = null;
                $post_data['woocommerce_upayments_knet_charge_type'] = null;
            }
        }

        return array(
            'post_data' => $post_data,
            'api_key_missing' => $api_key_missing,
            'multimerchant_missing' => $multimerchant_missing,
        );
    }

    /**
     * Sanitize the historical JSON-backed presentation field.
     *
     * Runtime Charge allocation continues to use the five legacy scalar
     * settings; this method does not broaden routing to arbitrary rules.
     *
     * @param mixed $value Raw field value.
     * @return string
     */
    public static function sanitize_multimerchant_accounts($value) {
        if (!is_string($value)) {
            return '[]';
        }

        $rules = json_decode(stripslashes($value), true);
        if (!is_array($rules)) {
            return '[]';
        }

        $sanitized_rules = array();
        foreach ($rules as $rule) {
            $sanitized_rules[] = array(
                'iban_number' => sanitize_text_field(isset($rule['iban_number']) ? $rule['iban_number'] : ''),
                'knet_charge' => sanitize_text_field(isset($rule['knet_charge']) ? $rule['knet_charge'] : ''),
                'knet_charge_type' => wc_clean(isset($rule['knet_charge_type']) ? $rule['knet_charge_type'] : ''),
                'cc_charge' => sanitize_text_field(isset($rule['cc_charge']) ? $rule['cc_charge'] : ''),
                'cc_charge_type' => wc_clean(isset($rule['cc_charge_type']) ? $rule['cc_charge_type'] : ''),
            );
        }
        $encoded = json_encode($sanitized_rules);
        return is_string($encoded) ? $encoded : '[]';
    }

    /**
     * Render the inherited single additional-merchant settings row.
     *
     * @param string   $key Field key.
     * @param array    $data WooCommerce field data.
     * @param callable $get_option Gateway option reader.
     * @param string   $gateway_id Gateway compatibility ID.
     * @param string   $domain Gateway text domain.
     * @return string
     */
    public static function render_multimerchant($key, array $data, callable $get_option, $gateway_id, $domain) {
        $default = array_key_exists('default', $data) ? $data['default'] : null;
        $settings = call_user_func($get_option, $key, $default);
        $conditions = array(
            'fixed' => __('Fixed', 'sucheckout-upayments'),
            'percentage' => __('Percentage', 'sucheckout-upayments'),
        );

        ob_start();
        ?>
        <tr valign="top" class="upayments-multimerchant-repeater">
            <th scope="row" class="titledesc"><?php echo esc_html($data['title']); ?></th>
            <td class="forminp forminp-<?php echo esc_attr(sanitize_title($data['type'])); ?>">
                <p class="description"><?php echo wp_kses_post($data['description']); ?></p>
                <div id="multimerchant_repeater_container">
                    <table class="widefat wc_input_multimerchant_repeater" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('IBAN Number', 'sucheckout-upayments'); ?></th>
                                <th><?php esc_html_e('Knet Charge', 'sucheckout-upayments'); ?></th>
                                <th><?php esc_html_e('Knet Charge Type', 'sucheckout-upayments'); ?></th>
                                <th><?php esc_html_e('CC Charge', 'sucheckout-upayments'); ?></th>
                                <th><?php esc_html_e('CC Charge Type', 'sucheckout-upayments'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="woocommerce_upayments_iban_number" data-field="iban_number" value="<?php echo esc_attr(call_user_func($get_option, 'iban_number')); ?>" placeholder="<?php esc_html_e('KWK00445...', 'sucheckout-upayments'); ?>" style="width: 400px;"/></td>
                                <td><input type="number" name="woocommerce_upayments_knet_charge" data-field="knet_charge" value="<?php echo esc_attr(call_user_func($get_option, 'knet_charge')); ?>" placeholder="<?php esc_html_e('0.000', 'sucheckout-upayments'); ?>" min="0.000" max="10.000" step="0.010"/></td>
                                <td>
                                    <select data-field="knet_charge_type" name="woocommerce_upayments_knet_charge_type">
                                        <option value=""><?php esc_html_e('Select', 'sucheckout-upayments'); ?></option>
                                        <?php foreach ($conditions as $value => $label) : ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($value, call_user_func($get_option, 'knet_charge_type')); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="woocommerce_upayments_cc_charge" data-field="cc_charge" value="<?php echo esc_attr(call_user_func($get_option, 'cc_charge')); ?>" placeholder="<?php esc_html_e('0.000', 'sucheckout-upayments'); ?>" min="0.000" max="10.000" step="0.010"/></td>
                                <td>
                                    <select data-field="cc_charge_type" name="woocommerce_upayments_cc_charge_type">
                                        <option value=""><?php esc_html_e('Select', 'sucheckout-upayments'); ?></option>
                                        <?php foreach ($conditions as $value => $label) : ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($value, call_user_func($get_option, 'cc_charge_type')); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="woocommerce_<?php echo esc_attr($gateway_id); ?>_<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($settings); ?>" />
            </td>
        </tr>
        <?php
        $output = ob_get_clean();
        return is_string($output) ? $output : '';
    }

    /**
     * Enqueue the inherited settings-page assets behind explicit page checks.
     *
     * @param string $plugin_url Plugin base URL.
     * @param string $gateway_id Gateway ID.
     * @param array  $query Request query.
     * @param string $screen_id Current screen ID.
     * @return void
     */
    public static function enqueue_admin_assets($plugin_url, $gateway_id, array $query, $screen_id) {
        if (isset($query['page'], $query['tab'], $query['section'])
            && $query['page'] == 'wc-settings'
            && $query['tab'] == 'checkout'
            && $query['section'] == $gateway_id
        ) {
            wp_enqueue_style('upayments-multimerchant-style', $plugin_url . 'assets/css/admin-style.css', array(), '3.0.0');
            wp_enqueue_script('upayments-multimerchant-repeater', $plugin_url . 'assets/js/multimerchant-repeater.js', array('jquery'), '3.0.0', true);
        }

        if ($screen_id === 'woocommerce_page_wc-settings'
            && isset($query['tab']) && $query['tab'] === 'checkout'
        ) {
            wp_enqueue_script('upayments-admin-logic', $plugin_url . 'assets/js/admin-settings.js', array('jquery'), '3.0.0', true);
            wp_add_inline_style('woocommerce_admin_styles', '.upayments-disabled-setting { opacity: 0.5; pointer-events: none; }');
        }
    }
}
