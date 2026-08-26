<?php
/**
 * Supplemental executable-binding guard for Architecture & Code-Quality.
 *
 * Static-only by design. This guard validates compatibility-critical global
 * hook/callback bindings and thin public wrappers without bootstrapping
 * WordPress or WooCommerce.
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

function arch2_has_namespace_declaration(array $tokens)
{
    foreach ($tokens as $token) {
        if ($token['id'] === T_NAMESPACE) {
            return true;
        }
    }
    return false;
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
    return in_array(
        $id,
        array(T_ENDIF, T_ENDFOR, T_ENDFOREACH, T_ENDWHILE, T_ENDSWITCH, T_ENDDECLARE),
        true
    );
}

function arch2_is_label_colon(array $tokens, $index)
{
    if ($index < 1
        || !isset($tokens[$index], $tokens[$index - 1])
        || $tokens[$index]['text'] !== ':'
        || $tokens[$index - 1]['id'] !== T_STRING) {
        return false;
    }

    $labelIndex = $index - 1;
    if ($labelIndex === 0) {
        return true;
    }

    $beforeLabel = $tokens[$labelIndex - 1]['text'];
    return $beforeLabel === ';'
        || $beforeLabel === '}'
        || ($beforeLabel === ':' && arch2_is_label_colon($tokens, $labelIndex - 1));
}

function arch2_is_direct_statement_start(array $tokens, $index)
{
    if ($index === 0) {
        return true;
    }

    $previous = $tokens[$index - 1]['text'];
    return $previous === ';'
        || $previous === '}'
        || ($previous === ':' && arch2_is_label_colon($tokens, $index - 1));
}

function arch2_is_direct_terminator(array $tokens, $index)
{
    if (!isset($tokens[$index]) || !arch2_is_direct_statement_start($tokens, $index)) {
        return false;
    }

    return in_array(
        $tokens[$index]['id'],
        array(T_RETURN, T_EXIT, T_THROW, T_GOTO),
        true
    );
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
                } elseif (($text === ';' || $text === ',')
                    && $paren === 0 && $bracket === 0 && $brace === 0) {
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
    if (arch2_has_namespace_declaration($tokens)) {
        return array('found' => false, 'body' => array());
    }

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
        if (arch2_is_direct_terminator($tokens, $i)) {
            return array('found' => false, 'body' => array());
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

function arch2_sequence_index(array $tokens, array $sequence)
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
            return $i;
        }
    }

    return false;
}

function arch2_has_sequence(array $tokens, array $sequence)
{
    return arch2_sequence_index($tokens, $sequence) !== false;
}

function arch2_gateway_callback_returns_registered_methods(array $body)
{
    $expected = array(
        array(T_VARIABLE, '$methods'),
        array(null, '['),
        array(null, ']'),
        array(null, '='),
        array(T_CONSTANT_ENCAPSED_STRING, 'WC_UPayments'),
        array(null, ';'),
        array(T_RETURN, 'return'),
        array(T_VARIABLE, '$methods'),
        array(null, ';'),
    );

    if (count($body) !== count($expected)) {
        return false;
    }

    foreach ($expected as $index => $want) {
        if ($body[$index]['id'] !== $want[0] || $body[$index]['text'] !== $want[1]) {
            return false;
        }
    }

    return true;
}

function arch2_class_body_tokens(array $tokens, $className)
{
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        if ($tokens[$i]['id'] !== T_CLASS) {
            continue;
        }

        $nameIndex = $i + 1;
        if (!isset($tokens[$nameIndex])
            || $tokens[$nameIndex]['id'] !== T_STRING
            || $tokens[$nameIndex]['text'] !== $className) {
            continue;
        }

        for ($j = $nameIndex + 1; $j < $count; $j++) {
            if ($tokens[$j]['text'] === ';') {
                break;
            }
            if ($tokens[$j]['text'] === '{') {
                return arch2_extract_body($tokens, $j);
            }
        }
    }

    return array();
}

function arch2_direct_public_method(array $classTokens, $methodName)
{
    $depth = 0;
    $count = count($classTokens);

    for ($i = 0; $i < $count; $i++) {
        $text = $classTokens[$i]['text'];

        if ($text === '{') {
            $depth++;
            continue;
        }
        if ($text === '}') {
            if ($depth > 0) {
                $depth--;
            }
            continue;
        }
        if ($depth !== 0 || $classTokens[$i]['id'] !== T_PUBLIC) {
            continue;
        }

        $functionIndex = null;
        for ($j = $i + 1; $j < $count; $j++) {
            if ($classTokens[$j]['text'] === ';' || $classTokens[$j]['text'] === '{') {
                break;
            }
            if ($classTokens[$j]['id'] === T_FUNCTION) {
                $functionIndex = $j;
                break;
            }
        }

        if ($functionIndex === null
            || !isset($classTokens[$functionIndex + 1])
            || $classTokens[$functionIndex + 1]['id'] !== T_STRING
            || $classTokens[$functionIndex + 1]['text'] !== $methodName) {
            continue;
        }

        for ($j = $functionIndex + 2; $j < $count; $j++) {
            if ($classTokens[$j]['text'] === ';') {
                return array('found' => false, 'body' => array());
            }
            if ($classTokens[$j]['text'] === '{') {
                return array(
                    'found' => true,
                    'body' => arch2_without_nested_functions(arch2_extract_body($classTokens, $j)),
                );
            }
        }
    }

    return array('found' => false, 'body' => array());
}

function arch2_code_without_strings(array $tokens)
{
    $code = '';
    foreach ($tokens as $token) {
        if ($token['id'] === T_CONSTANT_ENCAPSED_STRING) {
            $code .= '__STRING__';
        } else {
            $code .= $token['text'];
        }
    }
    return $code;
}

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
arch2_assert(!arch2_has_namespace_declaration($gatewayTokens), 'legacy main file remains in the global namespace');
arch2_assert($gatewayRegistration['found'], 'WooCommerce gateway registration is a direct executable global hook/callback pair');
arch2_assert(
    arch2_gateway_callback_returns_registered_methods($gatewayRegistration['body']),
    'gateway registration callback is exactly WC_UPayments append then methods return'
);
arch2_assert($availabilityRegistration['found'], 'availability registration is a direct executable global hook/callback pair');
arch2_assert($productMetaRegistration['found'], 'subscription product-meta registration is a direct executable global hook/callback pair');

$gatewayClass = arch2_class_body_tokens($gatewayTokens, 'WC_Upayments');
$statusMethod = arch2_direct_public_method($gatewayClass, 'get_payment_staus');
$statusDelegation = '\\Simplix\\Pay\\UPayments\\Security\\PublicOrderStatus::handle();';
arch2_assert($statusMethod['found'], 'historical public status-poll wrapper remains executable');
arch2_assert(
    $statusMethod['found'] && arch2_code_without_strings($statusMethod['body']) === $statusDelegation,
    'historical public status-poll wrapper directly delegates only to PublicOrderStatus'
);

$alternativeIfFixture = <<<'PHP'
<?php
if (false):
    echo 'dead';
    add_filter("woocommerce_available_payment_gateways", "enableUpaymentsGateway");
    function enableUpaymentsGateway($available_gateways) { return $available_gateways; }
endif;
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(
        arch2_tokens($alternativeIfFixture),
        'add_filter',
        'woocommerce_available_payment_gateways',
        'enableUpaymentsGateway'
    )['found'],
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
    !arch2_direct_top_level_hook_callback(
        arch2_tokens($alternativeForeachFixture),
        'add_filter',
        'woocommerce_payment_gateways',
        'addUpaymentsGatewayClass'
    )['found'],
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
    !arch2_direct_top_level_hook_callback(
        arch2_tokens($bracedFixture),
        'add_action',
        'woocommerce_process_product_meta',
        'saveCustomFieldData'
    )['found'],
    'matcher rejects braced conditional callback pair'
);

$bracelessFixture = <<<'PHP'
<?php
if (false)
    add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(
        arch2_tokens($bracelessFixture),
        'add_filter',
        'woocommerce_payment_gateways',
        'addUpaymentsGatewayClass'
    )['found'],
    'matcher rejects brace-less conditional registration'
);

$namespaceFixture = <<<'PHP'
<?php
namespace Simplix\Pay\UPayments;
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
PHP;
$namespaceTokens = arch2_tokens($namespaceFixture);
arch2_assert(arch2_has_namespace_declaration($namespaceTokens), 'namespace fixture is recognized as namespaced');
arch2_assert(
    !arch2_direct_top_level_hook_callback(
        $namespaceTokens,
        'add_filter',
        'woocommerce_payment_gateways',
        'addUpaymentsGatewayClass'
    )['found'],
    'matcher rejects string callbacks moved into an unbracketed namespace'
);

$inertFixture = <<<'PHP'
<?php
// add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
$dead = 'add_action("woocommerce_process_product_meta", "saveCustomFieldData");';
function addUpaymentsGatewayClass($methods) { return $methods; }
function saveCustomFieldData($post_id) {}
PHP;
arch2_assert(
    !arch2_direct_top_level_hook_callback(
        arch2_tokens($inertFixture),
        'add_filter',
        'woocommerce_payment_gateways',
        'addUpaymentsGatewayClass'
    )['found'],
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
    $arrowGateway['found'] && !arch2_gateway_callback_returns_registered_methods($arrowGateway['body']),
    'matcher strips arrow-function gateway append from callback body'
);

$conditionalAppendFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) {
    if (false) {
        $methods[] = "WC_UPayments";
    }
    return $methods;
}
PHP;
$conditionalAppend = arch2_direct_top_level_hook_callback(
    arch2_tokens($conditionalAppendFixture),
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
arch2_assert(
    $conditionalAppend['found']
        && !arch2_gateway_callback_returns_registered_methods($conditionalAppend['body']),
    'gateway semantic guard rejects conditional or unreachable append path'
);

$returnBeforeAppendFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) {
    return $methods;
    $methods[] = "WC_UPayments";
}
PHP;
$returnBeforeAppend = arch2_direct_top_level_hook_callback(
    arch2_tokens($returnBeforeAppendFixture),
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
arch2_assert(
    $returnBeforeAppend['found']
        && !arch2_gateway_callback_returns_registered_methods($returnBeforeAppend['body']),
    'gateway semantic guard rejects append after return'
);

$overwriteFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) {
    $methods[] = "WC_UPayments";
    $methods = array();
    return $methods;
}
PHP;
$overwriteGateway = arch2_direct_top_level_hook_callback(
    arch2_tokens($overwriteFixture),
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
arch2_assert(
    $overwriteGateway['found']
        && !arch2_gateway_callback_returns_registered_methods($overwriteGateway['body']),
    'gateway semantic guard rejects overwritten methods array before return'
);

$statusInertFixture = <<<'PHP'
<?php
class WC_Upayments {
    public function get_payment_staus() {
        // \Simplix\Pay\UPayments\Security\PublicOrderStatus::handle();
        $dead = '\Simplix\Pay\UPayments\Security\PublicOrderStatus::handle();';
        $nested = function () {
            \Simplix\Pay\UPayments\Security\PublicOrderStatus::handle();
        };
    }
}
PHP;
$statusInertTokens = arch2_tokens($statusInertFixture);
$statusInertClass = arch2_class_body_tokens($statusInertTokens, 'WC_Upayments');
$statusInertMethod = arch2_direct_public_method($statusInertClass, 'get_payment_staus');
arch2_assert(
    $statusInertMethod['found']
        && arch2_code_without_strings($statusInertMethod['body']) !== $statusDelegation,
    'status delegation guard ignores comment, string and nested-callable copies'
);

$terminatorFixtures = array(
    'return' => array('statement' => 'return;', 'label' => ''),
    'exit' => array('statement' => 'exit;', 'label' => ''),
    'throw' => array('statement' => 'throw new RuntimeException("halt");', 'label' => ''),
    'goto' => array('statement' => 'goto arch2_after;', 'label' => 'arch2_after: ;'),
    'label-prefixed return' => array('statement' => 'arch2_stage: return;', 'label' => ''),
    'label-prefixed exit' => array('statement' => 'arch2_stage: exit;', 'label' => ''),
    'label-prefixed throw' => array(
        'statement' => 'arch2_stage: throw new RuntimeException("halt");',
        'label' => '',
    ),
    'label-prefixed goto' => array(
        'statement' => 'arch2_stage: goto arch2_after;',
        'label' => 'arch2_after: ;',
    ),
);
$protectedRegistrations = array(
    'gateway' => array(
        'hook_function' => 'add_filter',
        'hook_name' => 'woocommerce_payment_gateways',
        'callback_name' => 'addUpaymentsGatewayClass',
        'callback_body' => '$methods[] = "WC_UPayments"; return $methods;',
    ),
    'availability' => array(
        'hook_function' => 'add_filter',
        'hook_name' => 'woocommerce_available_payment_gateways',
        'callback_name' => 'enableUpaymentsGateway',
        'callback_body' => 'return $available_gateways;',
    ),
    'product-meta' => array(
        'hook_function' => 'add_action',
        'hook_name' => 'woocommerce_process_product_meta',
        'callback_name' => 'saveCustomFieldData',
        'callback_body' => 'return $post_id;',
    ),
);

foreach ($terminatorFixtures as $terminatorName => $fixture) {
    foreach ($protectedRegistrations as $stageName => $registration) {
        $terminatedSource = "<?php\n"
            . $fixture['statement'] . "\n"
            . $registration['hook_function'] . "('" . $registration['hook_name'] . "', '"
            . $registration['callback_name'] . "');\n"
            . 'function ' . $registration['callback_name'] . '($value) {'
            . $registration['callback_body'] . "}\n"
            . $fixture['label'] . "\n";
        arch2_assert(
            !arch2_direct_top_level_hook_callback(
                arch2_tokens($terminatedSource),
                $registration['hook_function'],
                $registration['hook_name'],
                $registration['callback_name']
            )['found'],
            "matcher rejects {$stageName} registration after direct {$terminatorName} terminator"
        );
    }
}

$conditionalTerminatorFixture = <<<'PHP'
<?php
if (false) { return; }
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
if (false) exit;
add_filter("woocommerce_available_payment_gateways", "enableUpaymentsGateway");
function enableUpaymentsGateway($available_gateways) { return $available_gateways; }
if (false):
    throw new RuntimeException("halt");
endif;
add_action('woocommerce_process_product_meta', 'saveCustomFieldData');
function saveCustomFieldData($post_id) { return $post_id; }
PHP;
$conditionalTokens = arch2_tokens($conditionalTerminatorFixture);
foreach ($protectedRegistrations as $stageName => $registration) {
    arch2_assert(
        arch2_direct_top_level_hook_callback(
            $conditionalTokens,
            $registration['hook_function'],
            $registration['hook_name'],
            $registration['callback_name']
        )['found'],
        "matcher accepts {$stageName} registration after conditional terminator"
    );
}

$validFixture = <<<'PHP'
<?php
add_filter("woocommerce_payment_gateways", "addUpaymentsGatewayClass");
function addUpaymentsGatewayClass($methods) { $methods[] = "WC_UPayments"; return $methods; }
add_action('woocommerce_process_product_meta', 'saveCustomFieldData');
function saveCustomFieldData($post_id) { return $post_id; }
PHP;
$validTokens = arch2_tokens($validFixture);
$validGateway = arch2_direct_top_level_hook_callback(
    $validTokens,
    'add_filter',
    'woocommerce_payment_gateways',
    'addUpaymentsGatewayClass'
);
$validProduct = arch2_direct_top_level_hook_callback(
    $validTokens,
    'add_action',
    'woocommerce_process_product_meta',
    'saveCustomFieldData'
);
arch2_assert($validGateway['found'], 'matcher accepts direct top-level gateway registration/callback');
arch2_assert(
    $validGateway['found'] && arch2_gateway_callback_returns_registered_methods($validGateway['body']),
    'gateway semantic guard accepts exact append-then-return callback'
);
arch2_assert($validProduct['found'], 'matcher accepts direct top-level product-meta registration/callback');

printf("\nArchitecture Runtime Bindings: %d PASS / %d FAIL\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);
