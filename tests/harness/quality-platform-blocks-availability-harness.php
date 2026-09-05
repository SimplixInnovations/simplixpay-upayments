<?php
/**
 * Q18 Blocks Availability Enforcement.
 *
 * Permanent behavior guard for the WooCommerce Blocks PHP adapter. This is
 * intentionally separate from the frozen H12 PHP ledger.
 */

namespace Automattic\WooCommerce\Blocks\Payments\Integrations {
    abstract class AbstractPaymentMethodType {
        protected $settings = array();
    }
}

namespace {
    $pass = 0;
    $fail = 0;

    function q18_assert($condition, $description) {
        global $pass, $fail;
        if ($condition) {
            $pass++;
            echo "PASS: " . $description . "\n";
        } else {
            $fail++;
            echo "FAIL: " . $description . "\n";
        }
    }

    $root = dirname(__DIR__, 2);
    $blocks_file = $root . '/includes/class-wc-gateway-upayments-blocks.php';
    $blocks_js = $root . '/assets/js/upayments-block.js';

    q18_assert(is_file($blocks_file), 'Blocks adapter source exists');
    q18_assert(is_file($blocks_js), 'Blocks client source exists');

    require_once $blocks_file;

    class Q18BlocksAvailabilityProbe extends \WCGatewayUPaymentsBlocks {
        public function set_settings_for_test($settings) {
            $this->settings = $settings;
        }
    }

    $probe = new Q18BlocksAvailabilityProbe('');

    $probe->set_settings_for_test(array('enabled' => 'yes'));
    q18_assert($probe->is_active() === true, 'canonical enabled=yes exposes Blocks method');

    $probe->set_settings_for_test(array('enabled' => 'no'));
    q18_assert($probe->is_active() === false, 'enabled=no suppresses Blocks method');

    $probe->set_settings_for_test(array());
    q18_assert($probe->is_active() === false, 'missing enabled flag fails closed');

    $probe->set_settings_for_test(array('enabled' => true));
    q18_assert($probe->is_active() === false, 'malformed boolean enabled flag fails closed');

    $probe->set_settings_for_test(array('enabled' => 'YES'));
    q18_assert($probe->is_active() === false, 'noncanonical enabled token fails closed');

    q18_assert($probe->get_name() === 'upayments', 'Blocks gateway identity remains upayments');

    $blocks_source = file_get_contents($blocks_file);
    $js_source = file_get_contents($blocks_js);
    q18_assert(
        is_string($blocks_source)
        && strpos($blocks_source, "get_option( 'woocommerce_upayments_settings', [] )") !== false,
        'canonical WooCommerce settings option identity remains exact'
    );
    q18_assert(
        is_string($js_source)
        && strpos($js_source, "name: 'upayments'") !== false,
        'Blocks client registration identity remains upayments'
    );

    echo "\nQ18 Blocks Availability Enforcement: " . $pass . " PASS / " . $fail . " FAIL\n";
    exit($fail === 0 ? 0 : 1);
}
