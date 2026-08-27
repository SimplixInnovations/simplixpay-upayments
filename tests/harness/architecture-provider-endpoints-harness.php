<?php
/**
 * A1 provider endpoint/mode resolver characterization harness.
 */

use Simplix\Pay\UPayments\Provider\EndpointResolver;

// Exercise the pure service before the shared bootstrap defines any WP/Woo stubs.
require_once dirname(__DIR__, 2) . '/src/Provider/EndpointResolver.php';

$pass = 0;
$fail = 0;

function arch4_assert($condition, $message)
{
    global $pass, $fail;
    if ($condition) {
        $pass++;
        echo "PASS: {$message}\n";
        return;
    }
    $fail++;
    echo "FAIL: {$message}\n";
}

$liveBase = 'https://apiv2api.upayments.com/api/v1/';
$sandboxBase = 'https://sandboxapi.upayments.com/api/v1/';
$routes = array(
    '' => '',
    'charge' => 'charge',
    'status path' => 'get-payment-status/track%2Fid',
    'leading slash' => '/charge',
    'query text' => 'get-payment-status?session_id=session-1',
    'numeric route' => 123,
    'null route' => null,
);

foreach (array(false => $liveBase, true => $sandboxBase) as $mode => $base) {
    $resolver = new EndpointResolver((bool) $mode);
    foreach ($routes as $label => $route) {
        arch4_assert(
            $resolver->resolve($route) === $base . $route,
            ($mode ? 'sandbox' : 'live') . " resolver preserves {$label} concatenation"
        );
    }

    arch4_assert(
        $resolver->create_customer_token() === $base . 'create-customer-unique-token',
        ($mode ? 'sandbox' : 'live') . ' resolver preserves create-token endpoint'
    );
    arch4_assert(
        $resolver->check_payment_button_status() === $base . 'check-payment-button-status',
        ($mode ? 'sandbox' : 'live') . ' resolver preserves payment-button endpoint'
    );
    arch4_assert(
        $resolver->retrieve_customer_cards() === $base . 'retrieve-customer-cards',
        ($mode ? 'sandbox' : 'live') . ' resolver preserves retrieve-cards endpoint'
    );
}

require_once __DIR__ . '/_bootstrap.php';

$gateway = new WC_Upayments();
foreach (array('no' => $liveBase, 'yes' => $sandboxBase, '' => $sandboxBase) as $setting => $base) {
    $gateway->testMode = $setting;
    arch4_assert($gateway->getAPIUrl() === $base, "gateway {$setting} mode preserves empty-route URL");
    arch4_assert($gateway->getAPIUrl('charge') === $base . 'charge', "gateway {$setting} mode delegates generic route");
    arch4_assert(
        $gateway->getAPIUrlForCreateToken() === $base . 'create-customer-unique-token',
        "gateway {$setting} mode delegates create-token URL"
    );
    arch4_assert(
        $gateway->getAPIUrlForCheckPaymentButtonStatus() === $base . 'check-payment-button-status',
        "gateway {$setting} mode delegates payment-button URL"
    );
    arch4_assert(
        $gateway->getAPIUrlForRetreiveCards() === $base . 'retrieve-customer-cards',
        "gateway {$setting} mode delegates retrieve-cards URL"
    );
}

$resolverSource = file_get_contents(dirname(__DIR__, 2) . '/src/Provider/EndpointResolver.php');
$gatewaySource = file_get_contents(dirname(__DIR__, 2) . '/UPayments.php');
arch4_assert(is_string($resolverSource), 'provider endpoint resolver source is readable');
arch4_assert(is_string($gatewaySource), 'gateway source is readable');
arch4_assert(
    strpos($resolverSource, 'namespace Simplix\\Pay\\UPayments\\Provider;') !== false,
    'resolver uses the Simplix Provider namespace'
);
arch4_assert(
    strpos($resolverSource, 'get_option(') === false
        && strpos($resolverSource, 'apply_filters(') === false
        && strpos($resolverSource, 'do_action(') === false
        && strpos($resolverSource, 'add_action(') === false
        && strpos($resolverSource, 'add_filter(') === false
        && strpos($resolverSource, 'wp_') === false
        && strpos($resolverSource, 'WC_') === false
        && strpos($resolverSource, '$GLOBALS') === false,
    'resolver has no WordPress, WooCommerce, hook or global-state dependency'
);
arch4_assert(
    substr_count($gatewaySource, 'apiv2api.upayments.com/api/v1/') === 0
        && substr_count($gatewaySource, 'sandboxapi.upayments.com/api/v1/') === 0,
    'gateway monolith no longer owns provider API bases'
);
arch4_assert(
    strpos($gatewaySource, "require_once __DIR__ . '/src/Provider/EndpointResolver.php';") !== false,
    'gateway bootstrap loads provider endpoint resolver explicitly'
);
arch4_assert(
    strpos($gatewaySource, 'new EndpointResolver($this->getMode())') !== false,
    'legacy public wrappers delegate mode selection to resolver'
);

echo "\nArchitecture Provider Endpoints: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
