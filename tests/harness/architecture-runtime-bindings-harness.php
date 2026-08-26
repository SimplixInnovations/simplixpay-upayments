<?php
/**
 * Supplemental executable-binding guard for Architecture & Code-Quality.
 *
 * This intentionally focuses on global hook/callback pairs that must remain
 * direct file-scope runtime statements. It closes control-flow and inert-text
 * false-positive paths without bootstrapping WordPress/WooCommerce.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function arch2_assert($condition, $message)
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

function arch2_read($root, $path)
{
    $full = $root . '/' . $path;
    if (!is_file($full)) {
        return '';
    }
    $contents = file_get_contents($full);
    return is_string($contents) ? $contents : '';
}

function arch2_string_value($literal)
{
    if (!is_string($literal) || strlen($literal) < 2) {
        return null;
    }
    $quote = $literal[0];
    if (($quote !== "'" && $quote !== '"') || substr($literal, -1) !== $quote) {
        return null;
    }
    $body = substr($literal, 1, -1);
    if ($quote === "'") {
        return str_replace(array('\\\\', "\\'"), array('\\', "'"), $body);
    }
    return stripcslashes($body);
}

function arch2_tokens($source)
{
    if (!is_string($source) || $source === '') {
        return array();
    }
    $result = array();
    foreach (token_get_all($source) as $token) {
        if (!is_array($token)) {
            $result[] = array('id' => null, 'text' => $token);
            continue;
        }
        $id = $token[0];
        if ($id === T_WHITESPACE || $id === T_COMMENT || $id === T_DOC_COMMENT
            || $id === T_OPEN_TAG || $id === T_CLOSE_TAG) {
            continue;
        }
        $text = $token[1];
        if ($id === T_CONSTANT_ENCAPSED_STRING) {
            $decoded = arch2_string_value($text);
            $text = $decoded === null ? '__INVALID_STRING__' : $decoded;
        }
        $result[] = array('id' => $id, 'text' => $text);
    }
    return $result;
}

function arch2_alt_start_indexes(array $tokens)
{
    $starts = array();
    $controlIds = array(T_IF, T_FOR, T_FOREACH, T_WHILE, T_SWITCH, T_DECLARE);
    $count = count($tokens);
    for ($i = 0; $i < $count; $i++) {
        if (!in_array($tokens[$i]['id'], $controlIds, true)) {
            continue;
        }
        $paren = 0;
        $sawParen = false;
        for ($j = $i + 1; $j < $count; $j++) {
            $text = $tokens[$j]['text'];
            if ($text === '(') {
                $paren++;
                $sawParen = true;
                continue;
            }
            if ($text === ')') {
                if ($paren > 0) {
                    $paren--;
                }
                if ($sawParen && $paren === 0) {
                    $next = $j + 1;
                    if ($next < $count && $tokens[$next]['text'] === ':') {
                        $starts[$next] = true;
                    }
                    break;
                }
            }
            if (!$sawParen && ($text === ';' || $text === '{' || $text === ':')) {
                if ($text === ':') {
                    $starts[$j] = true;
                }
                break;
            }
        }
    }
    return $starts;
}

function arch2_is_alt_end($id)
{
    return in_array($id, array(T_ENDIF, T_ENDFOR, T_ENDFOREACH, T_ENDWHILE, T_ENDSWITCH, T_ENDDECLARE), true);
}

function arch2_hook_call_matches(array $tokens, $index, $hookFunction, $hookName, $callbackName)
{
    $expected = array(
        array(T_STRING, $hookFunction),
        array(null, '('),
        array(T_CONSTANT_ENCAPSED_STRING, $hookName),
        array(null, ','),
        array(T_CONSTANT_ENCAPSED_STRING, $callbackName),
        array(null, ')'),
        array(null, ';'),
    );
    if ($index + count($expected) > count($tokens)) {
        return false;
    }
    foreach ($expected as $offset => $want) {
        $actual = $tokens[$index + $offset];
        if ($actual['id'] !== $want[0] || $actual['text'] !== $want[1]) {
            return false;
        }
    }
    return true;
}

function arch2_extract_body(array $tokens, $openBraceIndex)
{
    $depth = 1;
    $body = array();
    $count = count($tokens);
    for ($i = $openBraceIndex + 1; $i < $count; $i++) {
        $text = $tokens[$i]['text'];
        if ($text === '{') {
            $depth++;
        } elseif ($text === '}') {
            $depth--;
            if ($depth === 0) {
                return $body;
            }
        }
        $body[] = $tokens[$i];
    }
    return array();
}

function arch2_without_nested_functions(array $tokens)
{
    $result = array();
    $count = count($tokens);
    for ($i = 0; $i < $count; $i++) {
        if ($tokens[$i]['id'] === T_FUNCTION) {
            $j = $i + 1;
            while ($j < $count && $tokens[$j]['text'] !== '{' && $tokens[$j]['text'] !== ';') {
                $j++;
            }
            if ($j < $count && $tokens[$j]['text'] === '{') {
                $depth = 1;
                $j++;
                while ($j < $count && $depth > 0) {
                    if ($tokens[$j]['text'] === '{') {
                        $depth++;
                    } elseif ($tokens[$j]['text'] === '}') {
                        $depth--;
                    }
                    $j++;
                }
            } elseif ($j < $count) {
                $j++;
            }
            $i = $j - 1;
            continue;
        }

        // T_FN exists only on runtimes that understand arrow functions.
        // Use defined()/constant() so this harness remains parseable/runnable on
        // the project's PHP 7.2 compatibility floor while still stripping arrow
        // expressions when tokenizing on PHP 7.4+ / CI PHP 8.x.
        if (defined('T_FN') && $tokens[$i]['id'] === constant('T_FN')) {
            $j = $i + 1;
            $paren = 0;
            $bracket = 0;
            $brace = 0;
            while ($j < $count) {
                $text = $tokens[$j]['text'];
                if ($text === '(') {
                    $paren++;
                } elseif ($text === ')') {
                    if ($paren > 0) {
                        $paren--;
                    }
                } elseif ($text === '[') {
                    $bracket++;
                } elseif ($text === ']') {
                    if ($bracket > 0) {
                        $bracket--;
                    }
                } elseif ($text === '{') {
                    $brace++;
                } elseif ($text === '}') {
                    if ($brace > 0) {
                        $brace--;
                    }
                } elseif (($text === ';' || $text === ',') && $paren === 0 && $bracket === 0 && $brace === 0) {
                    break;
                }
                $j++;
            }
            $i = $j;
            continue;
        }

        $result[] = $tokens[$i];
    }
    return $result;
}

function arch2_direct_top_level_hook_callback(array $tokens, $hookFunction, $hookName, $callbackName)
{
    $altStarts = arch2_alt_start_indexes($tokens);
    $braceDepth = 0;
    $altDepth = 0;
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        $id = $tokens[$i]['id'];
        $text = $tokens[$i]['text'];

        if (arch2_is_alt_end($id)) {
            if ($altDepth > 0) {
                $altDepth--;
            }
            continue;
        }
        if ($text === '{') {
            $braceDepth++;
            continue;
        }
        if ($text === '}') {
            if ($braceDepth > 0) {
                $braceDepth--;
            }
            continue;
        }
        if (isset($altStarts[$i])) {
            $altDepth++;
            continue;
        }
        if ($braceDepth !== 0 || $altDepth !== 0) {
            continue;
        }
        if (!arch2_hook_call_matches($tokens, $i, $hookFunction, $hookName, $callbackName)) {
            continue;
        }

        $previous = $i > 0 ? $tokens[$i - 1]['text'] : null;
        if ($previous !== null && $previous !== ';' && $previous !== '}') {
            continue;
        }

        $functionIndex = $i + 7;
        if (!isset($tokens[$functionIndex], $tokens[$functionIndex + 1])
            || $tokens[$functionIndex]['id'] !== T_FUNCTION
            || $tokens[$functionIndex + 1]['id'] !== T_STRING
            || $tokens[$functionIndex + 1]['text'] !== $callbackName) {
            continue;
        }

        for ($j = $functionIndex + 2; $j < $count; $j++) {
            if ($tokens[$j]['text'] === ';') {
                return array('found' => false, 'body' => array());
            }
            if ($tokens[$j]['text'] === '{') {
                return array(
                    'found' => true,
                    'body' => arch2_without_nested_functions(arch2_extract_body($tokens, $j)),
                );
            }
        }
    }

    return array('found' => false, 'body' => array());
}

function arch2_has_sequence(array $tokens, array $sequence)
{
    $count = count($tokens);
    $need = count($sequence);
    if ($need === 0 || $count < $need) {
        return false;
    }
    for ($i = 0; $i <= $count - $need; $i++) {
        $ok = true;
        for ($j = 0; $j < $need; $j++) {
            $want = $sequence[$j];
            if ($tokens[$i + $j]['id'] !== $want[0] || $tokens[$i + $j]['text'] !== $want[1]) {
                $ok = false;
                break;
            }
        }
        if ($ok) {
            return true;
        }
    }
    return false;
}

$gatewayAppendSequence = array(
    array(T_VARIABLE, '$methods'), array(null, '['), array(null, ']'), array(null, '='),
    array(T_CONSTANT_ENCAPSED_STRING, 'WC_UPayments'), array(null, ';'),
);

$gateway = arch2_read($root, 'UPayments.php');
$gatewayTokens = arch2_tokens($gateway);
$gatewayRegistration = arch2_direct_top_level_hook_callback(
    $gatewayTokens,
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
$availabilityRegistration = arch2_direct_top_level_hook_callback(
    $gatewayTokens,
    'add_filter',
    'woocommerce_available_payment_gateways',
    'enableUpaymentsGateway'
);
$productMetaRegistration = arch2_direct_top_level_hook_callback(
    $gatewayTokens,
    'add_action',
    'woocommerce_process_product_meta',
    'saveCustomFieldData'
);

arch2_assert($gateway !== '', 'UPayments.php is readable');
arch2_assert($gatewayRegistration['found'], 'WooCommerce gateway registration is a direct executable global hook/callback pair');
arch2_assert(
    arch2_has_sequence($gatewayRegistration['body'], $gatewayAppendSequence),
    'gateway registration callback directly appends WC_UPayments'
);
arch2_assert($availabilityRegistration['found'], 'availability registration is a direct executable global hook/callback pair');
arch2_assert($productMetaRegistration['found'], 'subscription product-meta registration is a direct executable global hook/callback pair');

$alternativeIfFixture = <<<'PHP'
<?php
if (false):
    echo 'dead';
    add_filter("woocommerce_available_payment_gateways", "enableUpaymentsGateway");
    function enableUpaymentsGateway($available_gateways) { return $available_gateways; }
endif;
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(arch2_tokens($alternativeIfFixture), 'add_filter', 'woocommerce_available_payment_gateways', 'enableUpaymentsGateway')['found'],
    'matcher rejects alternative-syntax if callback pair'
);

$alternativeForeachFixture = <<<'PHP'
<?php
foreach (array() as $x):
    echo 'dead';
    add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
    function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
endforeach;
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(arch2_tokens($alternativeForeachFixture), 'add_filter', 'woocommerce_payment_gateways', 'addUpaymentsGatewayClass')['found'],
    'matcher rejects alternative-syntax foreach callback pair'
);

$bracedFixture = <<<'PHP'
<?php
if (false) {
    add_action('woocommerce_process_product_meta', 'saveCustomFieldData');
    function saveCustomFieldData($post_id) {}
}
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(arch2_tokens($bracedFixture), 'add_action', 'woocommerce_process_product_meta', 'saveCustomFieldData')['found'],
    'matcher rejects braced conditional callback pair'
);

$bracelessFixture = <<<'PHP'
<?php
if (false)
    add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(arch2_tokens($bracelessFixture), 'add_filter', 'woocommerce_payment_gateways', 'addUpaymentsGatewayClass')['found'],
    'matcher rejects brace-less conditional registration'
);

$inertFixture = <<<'PHP'
<?php
// add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
$dead = 'add_action("woocommerce_process_product_meta", "saveCustomFieldData");';
function addUpaymentsGatewayClass($methods) { return $methods; }
function saveCustomFieldData($post_id) {}
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(arch2_tokens($inertFixture), 'add_filter', 'woocommerce_payment_gateways', 'addUpaymentsGatewayClass')['found'],
    'matcher ignores inert hook text'
);

$arrowFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) {
    $unused = fn() => $methods[] = "WC_UPayments";
    return $methods;
}
PHP;
$arrowGateway = arch2_direct_top_level_hook_callback(
    arch2_tokens($arrowFixture),
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
arch2_assert(
    $arrowGateway['found'] && !arch2_has_sequence($arrowGateway['body'], $gatewayAppendSequence),
    'matcher strips arrow-function gateway append from callback body'
);

$validFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
add_action('woocommerce_process_product_meta', 'saveCustomFieldData');
function saveCustomFieldData($post_id) { return $post_id; }
PHP;
$validTokens = arch2_tokens($validFixture);
$validGateway = arch2_direct_top_level_hook_callback($validTokens, 'add_filter', 'woocommerce_payment_gateways', 'addUpaymentsGatewayClass');
$validProduct = arch2_direct_top_level_hook_callback($validTokens, 'add_action', 'woocommerce_process_product_meta', 'saveCustomFieldData');
arch2_assert($validGateway['found'], 'matcher accepts direct top-level gateway registration/callback');
arch2_assert($validProduct['found'], 'matcher accepts direct top-level product-meta registration/callback');

printf("\nArchitecture Runtime Bindings: %d PASS / %d FAIL\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);
