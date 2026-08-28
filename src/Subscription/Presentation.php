<?php

namespace Simplix\Pay\UPayments\Subscription;

use UPayments\Subscription\Cron\Scheduler;
use UPayments\Subscription\Helpers\Utils;

/**
 * Inherited subscription product, admin and My Account presentation behavior.
 *
 * This class deliberately does not own Charge payloads, scheduler dispatch,
 * billing-attempt storage or subscription state mutation.
 */
final class Presentation {
    /** @return void */
    public static function register_product_class() {
        if (!class_exists('WooCommerce') || !class_exists('WC_Product_Simple')) {
            return;
        }
        require_once __DIR__ . '/WCProductCustomType.php';
    }

    /** @param array $types @return array */
    public static function add_custom_product_type($types) {
        $types['custom_type'] = __('Subscription Product', 'upayments');
        return $types;
    }

    /** @param string $classname @param string $product_type @return string */
    public static function map_custom_product_class($classname, $product_type) {
        if ($product_type === 'custom_type') {
            $classname = 'WCProductCustomType';
        }
        return $classname;
    }

    /** @return void */
    public static function custom_product_types() {
        if ('product' != get_post_type()) {
            return;
        }
        ?>
        <script type='text/javascript'>
            jQuery( document ).ready( function() {
                jQuery( '.options_group.pricing' ).addClass( 'show_if_custom_type' );
                jQuery( '.inventory_options' ).addClass( 'show_if_custom_type' );
                jQuery( 'select#product-type' ).change();
                jQuery('.show_if_simple').addClass('show_if_custom_type');
            });
        </script>
        <?php
    }

    /** @param array $tabs @return array */
    public static function add_custom_data_tab($tabs) {
        $tabs['custom_settings'] = array(
            'label' => __('Custom Settings', 'upayments'),
            'target' => 'custom_product_data_panel',
            'class' => array('show_if_custom_type'),
            'priority' => 25,
        );
        return $tabs;
    }

    /** @return void */
    public static function add_custom_data_panel() {
        ?>
        <div id="custom_product_data_panel" class="panel woocommerce_options_panel hidden">
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(array(
                    'id' => '_custom_field_id',
                    'label' => __('Custom Field', 'upayments'),
                    'placeholder' => 'Enter value here',
                    'desc_tip' => 'true',
                    'description' => __('This is a description of the field.', 'upayments'),
                ));
                ?>
            </div>
        </div>
        <?php
    }

    /** @param mixed $post_id @return void */
    public static function save_custom_field_data($post_id) {
        $post_id = absint($post_id);
        if ($post_id <= 0) {
            return;
        }
        if (empty($_POST['woocommerce_meta_nonce'])
            || !wp_verify_nonce(wp_unslash($_POST['woocommerce_meta_nonce']), 'woocommerce_save_data')
        ) {
            return;
        }
        if (empty($_POST['post_ID']) || absint($_POST['post_ID']) !== $post_id) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        $custom_field_value = isset($_POST['_custom_field_id'])
            && is_string($_POST['_custom_field_id'])
            ? sanitize_text_field(wp_unslash($_POST['_custom_field_id']))
            : '';
        if ($custom_field_value !== '') {
            update_post_meta($post_id, '_custom_field_id', $custom_field_value);
        }
    }

    /** @return void */
    public static function display_custom_field_on_frontend() {
        global $product;
        if (!is_object($product) || !$product->is_type('custom_type')) {
            return;
        }
        $custom_data = get_post_meta($product->get_id(), '_custom_field_id', true);
        if (!empty($custom_data)) {
            echo '<div class="custom-product-info">';
            echo '<strong style="background: #ffcc00; padding: 5px 10px; border-radius: 3px;">' . esc_html($custom_data) . '</strong>';
            echo '</div>';
        }
    }

    /** @param array $item_data @param array $cart_item @return array */
    public static function display_custom_data_in_cart($item_data, $cart_item) {
        $product_id = $cart_item['product_id'];
        $custom_value = get_post_meta($product_id, '_custom_field_id', true);
        if (!empty($custom_value)) {
            $item_data[] = array(
                'key' => __('Special Feature', 'upayments'),
                'value' => $custom_value,
                'display' => '',
            );
        }
        return $item_data;
    }

    /** @return void */
    public static function save_custom_data_to_order_items($item, $cart_item_key, $values, $order) {
        $product_id = $values['product_id'];
        $custom_value = get_post_meta($product_id, '_custom_field_id', true);
        if (!empty($custom_value)) {
            $item->add_meta_data(__('Special Feature', 'upayments'), $custom_value);
        }
    }

    /** @return void */
    public static function render_admin_order_summary($order) {
        foreach ($order->get_items('line_item') as $item) {
            $product = $item->get_product();
            if ($product->get_type() === 'custom_type') {
                $gateway = new \WC_Upayments();
                $gateway->render_subscription_summary($order);
            }
        }
    }

    /** @return void */
    public static function render_admin_summary($order) {
        $plan = $order->get_meta('_upay_subscription_plan');
        $interval = (int) $order->get_meta('_upay_subscription_interval');
        $auto_deduction = $order->get_meta('UPayments_AutoDeduction');
        $last_billed = $order->get_meta('_upay_last_billed_at');
        $started_at = $order->get_date_paid() ?: $order->get_date_completed() ?: $order->get_date_created();
        if (!$started_at) {
            return;
        }
        if (is_string($started_at)) {
            $started_at = new \DateTime($started_at);
        }
        $timezone = wp_timezone();
        $last_billed_dt = !empty($last_billed) ? new \DateTime($last_billed, $timezone) : null;
        $next_billing_dt = Scheduler::getNextBillingDate($started_at, $plan, $interval);
        if (!$next_billing_dt) {
            return;
        }
        $status = $order->get_meta('_upay_subscription_status');
        if ($status === 'active') {
            $status = '<span class="upay-status-active">' . ucfirst($status) . '</span>';
        } elseif ($status === 'paused') {
            $status = '<span class="upay-status-paused">' . ucfirst($status) . '</span>';
        } elseif ($status === 'cancelled') {
            $status = '<span class="upay-status-cancelled">' . ucfirst($status) . '</span>';
        } else {
            $status = ucfirst($status);
        }
        if (!$plan || $plan === 'one_time') {
            return;
        }
        if ($plan === 'yearly') {
            $period = 'Year';
        } elseif ($plan === 'monthly') {
            $period = 'Month';
        } elseif ($plan === 'weekly') {
            $period = 'Week';
        } else {
            $period = 'Day';
        }
        echo '<div class="upay-subscription-summary">';
        echo '<h4>' . esc_html__('Subscription Details', 'upayments') . '</h4>';
        if ($auto_deduction === 'no') {
            echo '<p><strong>Subscription Status:</strong> ' . wp_kses_post($status) . '</p>';
        }
        echo '<p><strong>Plan:</strong> ' . esc_html(ucfirst($plan)) . '</p>';
        echo '<p><strong>Interval:</strong> Every ' . esc_html($interval) . ' ' . esc_html($period) . '(s)</p>';
        if ($auto_deduction === 'yes' && empty($last_billed_dt)) {
            echo '<p><strong>Auto Deduction Order:</strong> Yes</p>';
        } else {
            if ($status !== 'cancelled') {
                echo '<p><strong>Next Billing Date:</strong> ' . esc_html($next_billing_dt->format('Y-m-d H:i:s')) . '</p>';
            }
            if (!empty($last_billed_dt)) {
                echo '<p><strong>Last Billed at:</strong> ' . esc_html($last_billed_dt->format('Y-m-d H:i:s')) . '</p>';
            }
        }
        echo '</div>';
    }

    /** @return mixed */
    public static function restrict_mixed_cart_products($passed, $product_id, $quantity, $domain) {
        if (!function_exists('WC') || !WC()->cart) {
            return $passed;
        }
        $product = wc_get_product($product_id);
        if (!$product) {
            return $passed;
        }
        $is_subscription_product = ($product->get_type() === 'custom_type');
        $cart_has_subscription = Utils::cartHasCustomType();
        $cart_has_normal = Utils::cartHasNormalProduct();
        if ($cart_has_subscription && !$is_subscription_product) {
            wc_add_notice(__('You can only add subscription products to the cart when a subscription item is present.', $domain), 'error');
            return false;
        }
        if ($cart_has_normal && $is_subscription_product) {
            wc_add_notice(__('Subscription products cannot be added together with normal products. Please complete your current purchase first.', $domain), 'error');
            return false;
        }
        return $passed;
    }

    /** @return void */
    public static function render_subscription_badge() {
        global $product;
        if (!$product instanceof \WC_Product || $product->get_type() !== 'custom_type') {
            return;
        }
        echo '<span class="upay-subscription-badge"><strong>🔁 Subscription</strong></span>';
    }

    /** @return void */
    public static function render_account_order_details($order) {
        if (!$order instanceof \WC_Order || !is_user_logged_in()) {
            return;
        }
        if ((int) $order->get_user_id() !== get_current_user_id()) {
            return;
        }
        if ($order->get_meta('_upay_subscription_status') === 'cancelled') {
            return;
        }
        $plan = $order->get_meta('_upay_subscription_plan');
        $interval = (int) $order->get_meta('_upay_subscription_interval');
        if (!$plan) {
            return;
        }
        $started_at = $order->get_date_paid() ?: $order->get_date_completed() ?: $order->get_date_created();
        if (!$started_at) {
            return;
        }
        if (is_string($started_at)) {
            $started_at = new \DateTime($started_at);
        }
        $last_billed = $order->get_meta('_upay_last_billed_at');
        $last_billed_dt = !empty($last_billed) ? new \DateTime($last_billed, wp_timezone()) : null;
        $next_billing_dt = Scheduler::getNextBillingDate($started_at, $plan, $interval);
        if (!$next_billing_dt || !$interval) {
            return;
        }
        $plan_labels = array(
            'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly',
            'quarterly' => 'Quarterly', 'yearly' => 'Yearly',
        );
        $interval_labels = array(
            'daily' => array(1 => 'Every Day'),
            'weekly' => array(1 => 'Every Week', 2 => 'Every 2 Weeks', 3 => 'Every 3 Weeks'),
            'monthly' => array(1 => 'Every Month', 2 => 'Every 2 Months'),
            'quarterly' => array(1 => 'Every Quarter', 2 => 'Every 2 Quarters', 3 => 'Every 3 Quarters'),
            'yearly' => array(1 => 'Every Year'),
        );
        ?>
        <section class="woocommerce-subscription-details">
            <h2><?php esc_html_e('Subscription Details', 'woocommerce'); ?></h2>
            <table class="shop_table shop_table_responsive" style="border: 1px solid;">
                <tbody>
                    <tr><th style="border: 1px solid;"><?php esc_html_e('Plan', 'woocommerce'); ?></th><td style="border: 1px solid;"><?php echo esc_html(isset($plan_labels[$plan]) ? $plan_labels[$plan] : ucfirst($plan)); ?></td></tr>
                    <tr><th style="border: 1px solid;"><?php esc_html_e('Interval', 'woocommerce'); ?></th><td style="border: 1px solid;"><?php echo esc_html(isset($interval_labels[$plan][$interval]) ? $interval_labels[$plan][$interval] : $interval); ?></td></tr>
                    <tr><th style="border: 1px solid;"><?php esc_html_e('Started On', 'woocommerce'); ?></th><td style="border: 1px solid;"><?php echo esc_html($started_at ? $started_at->format('Y-m-d H:i:s') : '-'); ?></td></tr>
                    <?php if ($order->get_meta('UPayments_AutoDeduction') !== 'yes') { ?>
                        <tr><th style="border: 1px solid;"><?php esc_html_e('Last Billed On', 'woocommerce'); ?></th><td style="border: 1px solid;"><?php echo esc_html($last_billed_dt ? $last_billed_dt->format('Y-m-d H:i:s') : '-'); ?></td></tr>
                        <tr><th style="border: 1px solid;"><?php esc_html_e('Next Billing Date', 'woocommerce'); ?></th><td style="border: 1px solid;"><?php echo esc_html($next_billing_dt ? $next_billing_dt->format('Y-m-d H:i:s') : '-'); ?></td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
        <?php
        if ($order->get_meta('UPayments_AutoDeduction') === 'yes') {
            return;
        }
        $status = $order->get_meta('_upay_subscription_status') ?: 'active';
        $action = $status === 'paused' ? 'resume' : 'pause';
        $label = $status === 'paused' ? 'Resume Subscription' : 'Pause Subscription';
        $form_action = wc_get_account_endpoint_url('view-order') . $order->get_id();
        ?>
        <form method="post" class="upay-subscription-actions" action="<?php echo esc_url($form_action); ?>">
            <input type="hidden" name="upay_action" value="unsubscribe" />
            <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>" />
            <?php wp_nonce_field('upay_unsubscribe_' . $order->get_id(), '_wpnonce', false); ?>
            <button type="submit" class="button upay-unsubscribe-button" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to unsubscribe?', 'woocommerce')); ?>');"><?php esc_html_e('Unsubscribe', 'woocommerce'); ?></button>
        </form>
        <form method="post" class="upay-subscription-actions" action="<?php echo esc_url($form_action); ?>">
            <input type="hidden" name="upay_action" value="<?php echo esc_attr($action); ?>" />
            <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>" />
            <?php wp_nonce_field('upay_' . $action . '_' . $order->get_id(), '_wpnonce', false); ?>
            <button type="submit" class="button upay-pause-resume-button"><?php echo esc_html($label); ?></button>
        </form>
        <?php
    }

    /** @return void */
    public static function render_account_orders_filter() {
        $current = sanitize_text_field(isset($_GET['subscription_filter']) ? $_GET['subscription_filter'] : '');
        ?>
        <form method="get" class="upay-orders-filter" action="<?php echo esc_url(add_query_arg(null, null)); ?>">
            <input type="hidden" name="page_id" value="<?php echo esc_attr(isset($_GET['page_id']) ? $_GET['page_id'] : 12); ?>">
            <input type="hidden" name="orders" value="">
            <label for="subscription_filter">Select Order Type:</label>
            <select id="subscription_filter" name="subscription_filter" onchange="this.form.submit()">
                <option value="">All orders</option>
                <option value="active" <?php selected($current, 'active'); ?>>Active subscriptions</option>
                <option value="paused" <?php selected($current, 'paused'); ?>>Paused subscriptions</option>
                <option value="cancelled" <?php selected($current, 'cancelled'); ?>>Cancelled subscriptions</option>
            </select>
        </form>
        <?php
    }

    /** @param array $args @return array */
    public static function filter_account_orders_query($args) {
        if (empty($_GET['subscription_filter'])) {
            return $args;
        }
        $filter = sanitize_text_field($_GET['subscription_filter']);
        $args['meta_query'][] = array('key' => '_upay_subscription_status', 'value' => $filter);
        return $args;
    }

    /** @param array $columns @return array */
    public static function filter_account_orders_columns($columns) {
        $new_columns = array();
        foreach ($columns as $key => $label) {
            $new_columns[$key] = $label;
            if ($key === 'order-status') {
                $new_columns['order_type'] = __('Type', 'woocommerce');
                $new_columns['order_status'] = __('Status', 'woocommerce');
            }
        }
        return $new_columns;
    }

    /** @return void */
    public static function render_account_order_type($order) {
        echo $order->get_meta('UPayments_AutoDeduction') === 'yes'
            ? __('Auto Deduction', 'woocommerce')
            : __('Regular', 'woocommerce');
    }

    /** @return void */
    public static function render_account_subscription_status($order) {
        $status = $order->get_meta('_upay_subscription_status');
        if (!$status) {
            echo '—';
            return;
        }
        echo '<span class="upay-status upay-status-' . esc_attr($status) . '">' . esc_html(ucfirst($status)) . '</span>';
    }
}
