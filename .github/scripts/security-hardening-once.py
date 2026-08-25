from pathlib import Path
import re


def replace_once(text, old, new, label):
    count = text.count(old)
    if count != 1:
        raise SystemExit(f"{label}: expected exactly one match, found {count}")
    return text.replace(old, new, 1)


def regex_once(text, pattern, replacement, label):
    updated, count = re.subn(pattern, replacement, text, count=1, flags=re.S)
    if count != 1:
        raise SystemExit(f"{label}: expected exactly one regex match, found {count}")
    return updated


root = Path('.')

gateway_path = root / 'UPayments.php'
gateway = gateway_path.read_text(encoding='utf-8')

# SEC-01: inherited gateway method becomes defense-in-depth delegate. The
# priority-5 PaymentLifecycle interception is the primary remote boundary.
gateway = regex_once(
    gateway,
    r'''        public function get_payment_staus\(\)\n        \{.*?\n        \}\n\n        /\*\*\n         \* Execute a hardened authenticated UPayments HTTP request\.''',
    '''        public function get_payment_staus()\n        {\n            \\Simplix\\Pay\\UPayments\\Security\\PublicOrderStatus::handle();\n        }\n\n        /**\n         * Execute a hardened authenticated UPayments HTTP request.''',
    'SEC-01 legacy status delegate',
)

# SEC-03: remove unnecessary third-party checkout stylesheet dependencies.
gateway = replace_once(
    gateway,
    '''            // Always enqueue core scripts (e.g., utility functions, global validation)\n            wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Almarai&display=swap');\n            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');\n''',
    '''            // Checkout must not depend on third-party font/icon CDNs.\n            // Use site/system typography and plugin-local presentation only.\n''',
    'SEC-03 checkout CDN removal',
)

# SEC-04: plain provider/order metadata is text, never post HTML.
gateway = replace_once(
    gateway,
    '''<strong id="upayment-status-holder-strong"><?php echo wp_kses_post($payment_status); ?></strong>''',
    '''<strong id="upayment-status-holder-strong"><?php echo esc_html($payment_status); ?></strong>''',
    'SEC-04 payment status escaping',
)
gateway = replace_once(
    gateway,
    '''<strong id="upayment-id-holder-strong"><?php echo wp_kses_post($upayment_id); ?></strong>''',
    '''<strong id="upayment-id-holder-strong"><?php echo esc_html($upayment_id); ?></strong>''',
    'SEC-04 payment id escaping',
)
gateway = regex_once(
    gateway,
    r'''                        <\?php\n                            if \(isset\(\$status_message\) && !empty\(\$status_message\)\)\{\n                                echo \$status_message;\n                            \}else\{\n                                esc_html_e\("Your order is cancelled\.", \$this->domain\);\n                            \}\n                        \?>''',
    '''                        <?php esc_html_e("Your order is cancelled.", $this->domain); ?>''',
    'SEC-04 cancelled message escaping',
)

# SEC-04: stored settings are escaped in attribute context.
for field in ('iban_number', 'knet_charge', 'cc_charge'):
    gateway = replace_once(
        gateway,
        f'''value="<?php echo $this->get_option('{field}'); ?>"''',
        f'''value="<?php echo esc_attr( $this->get_option('{field}') ); ?>"''',
        f'SEC-04 {field} attribute escaping',
    )

# SEC-05: explicitly mirror WooCommerce's product-meta save boundary rather
# than relying only on the upstream hook provenance.
gateway = regex_once(
    gateway,
    r'''function saveCustomFieldData\( \$post_id \) \{\n    \$custom_field_value = isset\( \$_POST\['_custom_field_id'\] \) \? \$_POST\['_custom_field_id'\] : '';\n    \n    if \( ! empty\( \$custom_field_value \) \) \{\n        update_post_meta\( \$post_id, '_custom_field_id', sanitize_text_field\( \$custom_field_value \) \);\n    \}\n\}''',
    '''function saveCustomFieldData( $post_id ) {\n    $post_id = absint( $post_id );\n    if ( $post_id <= 0 ) {\n        return;\n    }\n\n    if ( empty( $_POST['woocommerce_meta_nonce'] )\n        || ! wp_verify_nonce( wp_unslash( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' )\n    ) {\n        return;\n    }\n\n    if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {\n        return;\n    }\n\n    if ( ! current_user_can( 'edit_post', $post_id ) ) {\n        return;\n    }\n\n    $custom_field_value = isset( $_POST['_custom_field_id'] )\n        && is_string( $_POST['_custom_field_id'] )\n        ? sanitize_text_field( wp_unslash( $_POST['_custom_field_id'] ) )\n        : '';\n\n    if ( $custom_field_value !== '' ) {\n        update_post_meta( $post_id, '_custom_field_id', $custom_field_value );\n    } else {\n        delete_post_meta( $post_id, '_custom_field_id' );\n    }\n}''',
    'SEC-05 product meta authorization',
)

# SEC-02: render state-changing subscription controls as POST forms, not GET
# links containing replayable nonce-bearing mutation URLs.
gateway = regex_once(
    gateway,
    r'''        if \(!\$isAutoDeductionOrder\) \{\n            \$unsubscribe_url = wp_nonce_url\(\n                add_query_arg\(\[\n                    'upay_action' => 'unsubscribe',\n                    'order_id'    => \$order->get_id\(\),\n                \], wc_get_account_endpoint_url\('view-order'\)\),\n                'upay_unsubscribe_' \. \$order->get_id\(\)\n            \);\n    \?>\n    <p class="upay-subscription-actions">\n        <a href="<\?php echo esc_url\(\$unsubscribe_url\); \?>"\n            class="button upay-unsubscribe-button"\n            onclick="return confirm\('<\?php esc_attr_e\('Are you sure you want to unsubscribe\?', 'woocommerce'\); \?>'\);">\n            <\?php esc_html_e\('Unsubscribe', 'woocommerce'\); \?>\n        </a>\n    </p>\n\n    <\?php\n            \$status = \$order->get_meta\('_upay_subscription_status'\) \?: 'active';\n            \$action = \$status === 'paused' \? 'resume' : 'pause';\n            \$label  = \$status === 'paused' \? 'Resume Subscription' : 'Pause Subscription';\n\n            \$url = wp_nonce_url\(\n                add_query_arg\(\[\n                    'upay_action' => \$action,\n                    'order_id'    => \$order->get_id\(\),\n                \], wc_get_account_endpoint_url\('view-order'\)\),\n                'upay_' \. \$action \. '_' \. \$order->get_id\(\)\n            \);\n    \?>\n    <p class="upay-subscription-actions">\n        <a href="<\?php echo esc_url\(\$url\); \?>" class="button upay-pause-resume-button">\n            <\?php echo esc_html\(\$label\); \?>\n        </a>\n    </p>\n    <\?php\n    \}\n''',
    '''        if (!$isAutoDeductionOrder) {\n            $status = $order->get_meta('_upay_subscription_status') ?: 'active';\n            $action = $status === 'paused' ? 'resume' : 'pause';\n            $label  = $status === 'paused' ? 'Resume Subscription' : 'Pause Subscription';\n            $form_action = wc_get_account_endpoint_url('view-order') . $order->get_id();\n    ?>\n    <form method="post" class="upay-subscription-actions" action="<?php echo esc_url($form_action); ?>">\n        <input type="hidden" name="upay_action" value="unsubscribe" />\n        <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>" />\n        <?php wp_nonce_field('upay_unsubscribe_' . $order->get_id(), '_wpnonce', false); ?>\n        <button type="submit" class="button upay-unsubscribe-button"\n            onclick="return confirm('<?php echo esc_js(__('Are you sure you want to unsubscribe?', 'woocommerce')); ?>');">\n            <?php esc_html_e('Unsubscribe', 'woocommerce'); ?>\n        </button>\n    </form>\n\n    <form method="post" class="upay-subscription-actions" action="<?php echo esc_url($form_action); ?>">\n        <input type="hidden" name="upay_action" value="<?php echo esc_attr($action); ?>" />\n        <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>" />\n        <?php wp_nonce_field('upay_' . $action . '_' . $order->get_id(), '_wpnonce', false); ?>\n        <button type="submit" class="button upay-pause-resume-button">\n            <?php echo esc_html($label); ?>\n        </button>\n    </form>\n    <?php\n    }\n''',
    'SEC-02 subscription POST forms',
)

# SEC-02: state-changing handler accepts POST only, enforces exact owner,
# UPayments/subscription object preconditions, and valid state transitions.
gateway = regex_once(
    gateway,
    r'''add_action\('init', function \(\) \{\n    \$action = isset\(\$_GET\['upay_action'\]\)\n        \? sanitize_key\(wp_unslash\(\$_GET\['upay_action'\]\)\)\n        : '';\n\n    \$order_id = isset\(\$_GET\['order_id'\]\)\n        \? absint\(wp_unslash\(\$_GET\['order_id'\]\)\)\n        : 0;''',
    '''add_action('init', function () {\n    $method = isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD'])\n        ? strtoupper($_SERVER['REQUEST_METHOD'])\n        : '';\n    if ($method !== 'POST') {\n        return;\n    }\n\n    $action = isset($_POST['upay_action']) && is_string($_POST['upay_action'])\n        ? sanitize_key(wp_unslash($_POST['upay_action']))\n        : '';\n\n    $order_id = isset($_POST['order_id'])\n        ? absint(wp_unslash($_POST['order_id']))\n        : 0;''',
    'SEC-02 subscription POST parser',
)

gateway = replace_once(
    gateway,
    '''    $order = wc_get_order($order_id);\n    if (!$order) {\n        return;\n    }\n\n    // Authorization: nonce is CSRF protection, not authorization.\n    if (!is_user_logged_in() || get_current_user_id() !== $order->get_user_id()) {\n        wc_add_notice(__('Unauthorized request.', 'woocommerce'), 'error');\n        wp_safe_redirect(wc_get_account_endpoint_url('orders'));\n        exit;\n    }\n\n    // Nonce verification: required for every state-changing action.\n    $nonce = isset($_GET['_wpnonce'])\n        ? sanitize_text_field(wp_unslash($_GET['_wpnonce']))\n        : '';\n''',
    '''    $order = wc_get_order($order_id);\n    if (!$order) {\n        return;\n    }\n\n    // Authorization: nonce is CSRF protection, never object authorization.\n    if (!is_user_logged_in() || get_current_user_id() !== (int) $order->get_user_id()) {\n        wc_add_notice(__('Unauthorized request.', 'woocommerce'), 'error');\n        wp_safe_redirect(wc_get_account_endpoint_url('orders'));\n        exit;\n    }\n\n    // Object contract: this customer action belongs only to manual UPayments\n    // subscription orders. Auto-deduction orders remain scheduler-controlled.\n    $plan = $order->get_meta('_upay_subscription_plan');\n    $interval = (int) $order->get_meta('_upay_subscription_interval');\n    $allowed_intervals = array(\n        'daily' => array(1),\n        'weekly' => array(1, 2, 3),\n        'monthly' => array(1, 2),\n        'quarterly' => array(1, 2, 3),\n        'yearly' => array(1),\n    );\n    if ((string) $order->get_payment_method() !== 'upayments'\n        || $order->get_meta('UPayments_AutoDeduction') === 'yes'\n        || !is_string($plan)\n        || !isset($allowed_intervals[$plan])\n        || !in_array($interval, $allowed_intervals[$plan], true)\n    ) {\n        wc_add_notice(__('Invalid subscription request.', 'woocommerce'), 'error');\n        wp_safe_redirect(wc_get_account_endpoint_url('orders'));\n        exit;\n    }\n\n    $current_status = $order->get_meta('_upay_subscription_status') ?: 'active';\n    $transition_allowed = ($action === 'unsubscribe' && in_array($current_status, array('active', 'paused'), true))\n        || ($action === 'pause' && $current_status === 'active')\n        || ($action === 'resume' && $current_status === 'paused');\n    if (!$transition_allowed) {\n        wc_add_notice(__('Invalid subscription state transition.', 'woocommerce'), 'error');\n        wp_safe_redirect(wc_get_account_endpoint_url('view-order') . $order_id);\n        exit;\n    }\n\n    // Nonce verification: required for every state-changing action.\n    $nonce = isset($_POST['_wpnonce']) && is_string($_POST['_wpnonce'])\n        ? sanitize_text_field(wp_unslash($_POST['_wpnonce']))\n        : '';\n''',
    'SEC-02 subscription authorization and preflight',
)

gateway_path.write_text(gateway, encoding='utf-8')

# Intercept the status poll in the priority-5 lifecycle before inherited priority 10.
lifecycle_path = root / 'src/Payment/PaymentLifecycle.php'
lifecycle = lifecycle_path.read_text(encoding='utf-8')
lifecycle = replace_once(
    lifecycle,
    '''namespace Simplix\\Pay\\UPayments\\Payment;\n\ndefined('ABSPATH') || exit;\n\nrequire_once __DIR__ . '/ProviderResult.php';''',
    '''namespace Simplix\\Pay\\UPayments\\Payment;\n\ndefined('ABSPATH') || exit;\n\nuse Simplix\\Pay\\UPayments\\Security\\PublicOrderStatus;\n\nrequire_once dirname(__DIR__) . '/Security/PublicOrderStatus.php';\nrequire_once __DIR__ . '/ProviderResult.php';''',
    'SEC-01 lifecycle security dependency',
)
lifecycle = replace_once(
    lifecycle,
    '''        if (array_key_exists('get_order_status', $get)) {\n            return;\n        }''',
    '''        if (array_key_exists('get_order_status', $get)) {\n            PublicOrderStatus::handle();\n            return;\n        }''',
    'SEC-01 priority-5 status interception',
)
lifecycle_path.write_text(lifecycle, encoding='utf-8')

# Checkout templates: explicit GET display flags only, and local chevron text.
for template_name in ('templates/new-design-form.php', 'templates/old-design-form.php'):
    path = root / template_name
    text = path.read_text(encoding='utf-8')
    count = text.count('$_REQUEST')
    if count < 1:
        raise SystemExit(f'{template_name}: expected at least one $_REQUEST marker')
    text = text.replace('$_REQUEST', '$_GET')
    path.write_text(text, encoding='utf-8')

new_template_path = root / 'templates/new-design-form.php'
new_template = new_template_path.read_text(encoding='utf-8')
chevron_count = new_template.count('<i class="fa fa-chevron-right"></i>')
if chevron_count < 1:
    raise SystemExit('new template: expected Font Awesome chevrons')
new_template = new_template.replace(
    '<i class="fa fa-chevron-right"></i>',
    '<span class="upay-chevron" aria-hidden="true">&#8250;</span>'
)
new_template_path.write_text(new_template, encoding='utf-8')

print('Security hardening replacements applied successfully.')
