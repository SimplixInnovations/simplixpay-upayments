<?php

$q2_pass = 0;
$q2_fail = 0;
$q2_root = dirname(__DIR__, 2);

function q2_assert($condition, $label) {
    global $q2_pass, $q2_fail;

    if ($condition) {
        ++$q2_pass;
        echo "PASS: {$label}\n";
        return;
    }

    ++$q2_fail;
    echo "FAIL: {$label}\n";
}

function q2_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q2_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q2_contains($source, $needle) {
    return strpos($source, $needle) !== false;
}

$composer = json_decode(q2_read($q2_root, 'composer.json'), true);
$phpstan = q2_read($q2_root, 'phpstan.neon.dist');
$tests = q2_read($q2_root, 'tests/unit/Payment/CheckoutPayloadTest.php');
$checkout = q2_read($q2_root, 'src/Payment/CheckoutPayload.php');
$runtime = q2_read($q2_root, 'UPayments.php');
$workflow = q2_read($q2_root, '.github/workflows/quality-gates.yml');
$quality_record = q2_read($q2_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q2_read($q2_root, 'docs/project/PROJECT-STATUS.md');
$readme = q2_read($q2_root, 'README.md');

q2_assert(is_array($composer), 'Composer manifest is valid JSON');
q2_assert(isset($composer['require']) && $composer['require'] === array('php' => '>=7.2'), 'Composer still has no production package dependency');
q2_assert(isset($composer['config']['allow-plugins']) && $composer['config']['allow-plugins'] === false, 'Composer plugin execution remains disabled');
q2_assert(!q2_contains($runtime, 'vendor/autoload.php'), 'runtime still does not load development Composer code');

q2_assert(q2_contains($phpstan, 'level: 5'), 'PHPStan level remains explicit');
q2_assert(q2_contains($phpstan, 'phpVersion: 70200'), 'PHPStan target remains the declared PHP floor');
foreach (array(
    'src/Payment/CheckoutPayload.php',
    'src/Payment/ProviderResult.php',
    'src/Provider/EndpointResolver.php',
) as $path) {
    q2_assert(q2_contains($phpstan, $path), "PHPStan owns characterized module: {$path}");
}
q2_assert(!q2_contains($phpstan, 'baseline'), 'Q2 expansion remains baseline-free');

foreach (array(
    '@param mixed $a First defensive boundary value.',
    '@param mixed $amount_str Defensive boundary input;',
    '@param mixed $payload_json Encoded payload with sentinels.',
    '@param mixed $uri Raw REQUEST_URI boundary value.',
    '@param mixed $is_rest_request REST_REQUEST state.',
    '@param mixed $qty        Strict positive integer quantity boundary value.',
    '@param mixed $numer_str Strict positive integer digit-string boundary value.',
    '@param mixed $value Provider-bound text candidate.',
) as $boundary_doc) {
    q2_assert(q2_contains($checkout, $boundary_doc), "defensive boundary typing is explicit: {$boundary_doc}");
}

q2_assert(substr_count($tests, 'public function test_') >= 16, 'CheckoutPayload PHPUnit characterization has at least sixteen focused tests');
foreach (array(
    'field_present(',
    'parse_save_card_strict(',
    'parse_payment_source_strict(',
    'is_valid_subscription_interval(',
    'validate_provider_positive_decimal(',
    'validate_provider_nonnegative_decimal(',
    'compute_provider_unit_price_decimal(',
    'digit_long_divide(',
    'digit_long_divide_remainder(',
    'canonicalize_provider_decimal_string(',
    'inject_amount_token_into_payload_json(',
    'get_max_length_for_sentinel(',
    'normalize_store_api_route(',
    'is_store_api_checkout_request(',
    'truncate_provider_text(',
) as $method_call) {
    q2_assert(q2_contains($tests, 'CheckoutPayload::' . $method_call), "PHPUnit characterizes CheckoutPayload::{$method_call}");
}

q2_assert(q2_contains($workflow, 'quality-platform-static-analysis-harness.php'), 'Q2 harness is mandatory in Quality Gates');
q2_assert(q2_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still runs after upstream failure');
q2_assert(q2_contains($workflow, 'QUALITY_PLATFORM_RESULT: ${{ needs.quality-platform.result }}'), 'protected H12 aggregator still reads quality result');
q2_assert(q2_contains($workflow, 'PHP_SYNTAX_RESULT: ${{ needs.php-syntax-compatibility.result }}'), 'protected H12 aggregator still reads syntax result');

q2_assert(q2_contains($quality_record, '**Status:** Q2 / IMPLEMENTATION'), 'quality record owns Q2');
foreach (array(
    '936e4630c83f7a92cbc4c77f061626e2b0c0c800',
    '473543cd08515eedd764a4b1ef7b6581590d13a1',
    'Quality Gates run #177',
    '9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362',
    'Quality Gates run #178',
    'implementation branch deleted',
) as $closure_evidence) {
    q2_assert(q2_contains($quality_record, $closure_evidence), "Q1 closure evidence is pinned: {$closure_evidence}");
}
q2_assert(q2_contains($status, '| Current program gate | **Full Automated Quality Platform — Q2** |'), 'project status advances to Quality Platform Q2');
q2_assert(q2_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q2**.'), 'README advances to Quality Platform Q2');

echo "\nQ2 Checkout Payload Analysis: {$q2_pass} PASS / {$q2_fail} FAIL\n";
exit($q2_fail === 0 ? 0 : 1);
