<?php
/**
 * Architecture bootstrap/execution-path guard.
 *
 * Static-only by design. This complements the architecture foundation and
 * runtime-binding harnesses by proving that the legacy gateway class is
 * reachable from the registered plugins_loaded callback and that the
 * protected gateway ID assignment is a direct constructor statement.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function arch3_assert($condition, $message)
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

function arch3_read($root, $path)
{
    $full = $root . '/' . $path;
    if (!is_file($full)) {
        return '';
    }
    $contents = file_get_contents($full);
    return is_string($contents) ? $contents : '';
}

function arch3_string_value($literal)
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

function arch3_tokens($source)
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
            $decoded = arch3_string_value($text);
            $text = $decoded === null ? '__INVALID_STRING__' : $decoded;
        }

        $result[] = array('id' => $id, 'text' => $text);
    }

    return $result;
}

function arch3_has_namespace_declaration(array $tokens)
{
    foreach ($tokens as $token) {
        if ($token['id'] === T_NAMESPACE) {
            return true;
        }
    }
    return false;
}

function arch3_alt_start_indexes(array $tokens)
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

function arch3_is_alt_end($id)
{
    return in_array(
        $id,
        array(T_ENDIF, T_ENDFOR, T_ENDFOREACH, T_ENDWHILE, T_ENDSWITCH, T_ENDDECLARE),
        true
    );
}

function arch3_extract_body(array $tokens, $openBraceIndex)
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

function arch3_hook_call_matches(array $tokens, $index, $hookFunction, $hookName, $callbackName)
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

function arch3_direct_top_level_hook_callback(array $tokens, $hookFunction, $hookName, $callbackName)
{
    if (arch3_has_namespace_declaration($tokens)) {
        return array('found' => false, 'body' => array());
    }

    $altStarts = arch3_alt_start_indexes($tokens);
    $braceDepth = 0;
    $altDepth = 0;
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        $id = $tokens[$i]['id'];
        $text = $tokens[$i]['text'];

        if (arch3_is_alt_end($id)) {
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
        if (!arch3_hook_call_matches($tokens, $i, $hookFunction, $hookName, $callbackName)) {
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
                    'body' => arch3_extract_body($tokens, $j),
                );
            }
        }
    }

    return array('found' => false, 'body' => array());
}

function arch3_direct_class(array $ownerBody, $className)
{
    $altStarts = arch3_alt_start_indexes($ownerBody);
    $braceDepth = 0;
    $altDepth = 0;
    $count = count($ownerBody);

    for ($i = 0; $i < $count; $i++) {
        $id = $ownerBody[$i]['id'];
        $text = $ownerBody[$i]['text'];

        if (arch3_is_alt_end($id)) {
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
        if ($braceDepth !== 0 || $altDepth !== 0 || $id !== T_CLASS) {
            continue;
        }

        $previous = $i > 0 ? $ownerBody[$i - 1]['text'] : null;
        if ($previous !== null && $previous !== ';' && $previous !== '}') {
            continue;
        }

        if (!isset($ownerBody[$i + 1])
            || $ownerBody[$i + 1]['id'] !== T_STRING
            || $ownerBody[$i + 1]['text'] !== $className) {
            continue;
        }

        for ($j = $i + 2; $j < $count; $j++) {
            if ($ownerBody[$j]['text'] === ';') {
                return array('found' => false, 'body' => array());
            }
            if ($ownerBody[$j]['text'] === '{') {
                return array(
                    'found' => true,
                    'body' => arch3_extract_body($ownerBody, $j),
                );
            }
        }
    }

    return array('found' => false, 'body' => array());
}

function arch3_direct_public_method(array $classBody, $methodName)
{
    $depth = 0;
    $count = count($classBody);

    for ($i = 0; $i < $count; $i++) {
        $text = $classBody[$i]['text'];
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
        if ($depth !== 0 || $classBody[$i]['id'] !== T_PUBLIC) {
            continue;
        }

        $functionIndex = null;
        for ($j = $i + 1; $j < $count; $j++) {
            if ($classBody[$j]['text'] === ';' || $classBody[$j]['text'] === '{') {
                break;
            }
            if ($classBody[$j]['id'] === T_FUNCTION) {
                $functionIndex = $j;
                break;
            }
        }

        if ($functionIndex === null
            || !isset($classBody[$functionIndex + 1])
            || $classBody[$functionIndex + 1]['id'] !== T_STRING
            || $classBody[$functionIndex + 1]['text'] !== $methodName) {
            continue;
        }

        for ($j = $functionIndex + 2; $j < $count; $j++) {
            if ($classBody[$j]['text'] === ';') {
                return array('found' => false, 'body' => array());
            }
            if ($classBody[$j]['text'] === '{') {
                return array(
                    'found' => true,
                    'body' => arch3_extract_body($classBody, $j),
                );
            }
        }
    }

    return array('found' => false, 'body' => array());
}

function arch3_has_direct_gateway_id_assignment(array $body)
{
    $expected = array(
        array(T_VARIABLE, '$this'),
        array(T_OBJECT_OPERATOR, '->'),
        array(T_STRING, 'id'),
        array(null, '='),
        array(T_CONSTANT_ENCAPSED_STRING, 'upayments'),
        array(null, ';'),
    );

    $altStarts = arch3_alt_start_indexes($body);
    $braceDepth = 0;
    $altDepth = 0;
    $count = count($body);
    $need = count($expected);

    for ($i = 0; $i < $count; $i++) {
        $id = $body[$i]['id'];
        $text = $body[$i]['text'];

        if (arch3_is_alt_end($id)) {
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

        $previous = $i > 0 ? $body[$i - 1]['text'] : null;
        if ($previous !== null && $previous !== ';' && $previous !== '}') {
            continue;
        }
        if ($i + $need > $count) {
            return false;
        }

        $matches = true;
        foreach ($expected as $offset => $want) {
            $actual = $body[$i + $offset];
            if ($actual['id'] !== $want[0] || $actual['text'] !== $want[1]) {
                $matches = false;
                break;
            }
        }
        if ($matches) {
            return true;
        }
    }

    return false;
}

$gateway = arch3_read($root, 'UPayments.php');
$gatewayTokens = arch3_tokens($gateway);

$bootstrap = arch3_direct_top_level_hook_callback(
    $gatewayTokens,
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
$gatewayClass = $bootstrap['found']
    ? arch3_direct_class($bootstrap['body'], 'WC_Upayments')
    : array('found' => false, 'body' => array());
$constructor = $gatewayClass['found']
    ? arch3_direct_public_method($gatewayClass['body'], '__construct')
    : array('found' => false, 'body' => array());

arch3_assert($gateway !== '', 'UPayments.php is readable');
arch3_assert(!arch3_has_namespace_declaration($gatewayTokens), 'legacy main file remains global for bootstrap callbacks');
arch3_assert($bootstrap['found'], 'plugins_loaded directly registers and owns woocommerceUpaymentsInit');
arch3_assert($gatewayClass['found'], 'woocommerceUpaymentsInit directly owns WC_Upayments declaration');
arch3_assert($constructor['found'], 'WC_Upayments constructor remains a direct public method');
arch3_assert(
    $constructor['found'] && arch3_has_direct_gateway_id_assignment($constructor['body']),
    'gateway ID upayments is an unconditional direct constructor statement'
);

$missingHookFixture = <<<'PHP'
<?php
function woocommerceUpaymentsInit() {
    class WC_Upayments {
        public function __construct() { $this->id = 'upayments'; }
    }
}
PHP;
arch3_assert(
    !arch3_direct_top_level_hook_callback(
        arch3_tokens($missingHookFixture),
        'add_action',
        'plugins_loaded',
        'woocommerceUpaymentsInit'
    )['found'],
    'bootstrap guard rejects unregistered init callback'
);

$nestedClassFixture = <<<'PHP'
<?php
add_action('plugins_loaded', 'woocommerceUpaymentsInit');
function woocommerceUpaymentsInit() {
    if (false) {
        class WC_Upayments {
            public function __construct() { $this->id = 'upayments'; }
        }
    }
}
PHP;
$nestedBootstrap = arch3_direct_top_level_hook_callback(
    arch3_tokens($nestedClassFixture),
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
arch3_assert(
    $nestedBootstrap['found']
        && !arch3_direct_class($nestedBootstrap['body'], 'WC_Upayments')['found'],
    'bootstrap guard rejects gateway class hidden in dead braced control flow'
);

$bracedIdFixture = <<<'PHP'
<?php
add_action('plugins_loaded', 'woocommerceUpaymentsInit');
function woocommerceUpaymentsInit() {
    class WC_Upayments {
        public function __construct() {
            if (false) { $this->id = 'upayments'; }
        }
    }
}
PHP;
$bracedBootstrap = arch3_direct_top_level_hook_callback(
    arch3_tokens($bracedIdFixture),
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
$bracedClass = arch3_direct_class($bracedBootstrap['body'], 'WC_Upayments');
$bracedConstructor = arch3_direct_public_method($bracedClass['body'], '__construct');
arch3_assert(
    $bracedConstructor['found'] && !arch3_has_direct_gateway_id_assignment($bracedConstructor['body']),
    'constructor guard rejects gateway ID hidden in braced conditional'
);

$bracelessIdFixture = <<<'PHP'
<?php
add_action('plugins_loaded', 'woocommerceUpaymentsInit');
function woocommerceUpaymentsInit() {
    class WC_Upayments {
        public function __construct() {
            if (false) $this->id = 'upayments';
        }
    }
}
PHP;
$bracelessBootstrap = arch3_direct_top_level_hook_callback(
    arch3_tokens($bracelessIdFixture),
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
$bracelessClass = arch3_direct_class($bracelessBootstrap['body'], 'WC_Upayments');
$bracelessConstructor = arch3_direct_public_method($bracelessClass['body'], '__construct');
arch3_assert(
    $bracelessConstructor['found'] && !arch3_has_direct_gateway_id_assignment($bracelessConstructor['body']),
    'constructor guard rejects gateway ID hidden in brace-less conditional'
);

$alternativeIdFixture = <<<'PHP'
<?php
add_action('plugins_loaded', 'woocommerceUpaymentsInit');
function woocommerceUpaymentsInit() {
    class WC_Upayments {
        public function __construct() {
            if (false):
                $this->id = 'upayments';
            endif;
        }
    }
}
PHP;
$alternativeBootstrap = arch3_direct_top_level_hook_callback(
    arch3_tokens($alternativeIdFixture),
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
$alternativeClass = arch3_direct_class($alternativeBootstrap['body'], 'WC_Upayments');
$alternativeConstructor = arch3_direct_public_method($alternativeClass['body'], '__construct');
arch3_assert(
    $alternativeConstructor['found'] && !arch3_has_direct_gateway_id_assignment($alternativeConstructor['body']),
    'constructor guard rejects gateway ID hidden in alternative-syntax conditional'
);

$validFixture = <<<'PHP'
<?php
add_action('plugins_loaded', 'woocommerceUpaymentsInit');
function woocommerceUpaymentsInit() {
    if (!class_exists('WooCommerce')) { return; }
    class WC_Upayments {
        public function __construct() {
            $this->id = 'upayments';
        }
    }
}
PHP;
$validBootstrap = arch3_direct_top_level_hook_callback(
    arch3_tokens($validFixture),
    'add_action',
    'plugins_loaded',
    'woocommerceUpaymentsInit'
);
$validClass = arch3_direct_class($validBootstrap['body'], 'WC_Upayments');
$validConstructor = arch3_direct_public_method($validClass['body'], '__construct');
arch3_assert($validBootstrap['found'], 'bootstrap guard accepts direct plugins_loaded registration/callback');
arch3_assert($validClass['found'], 'bootstrap guard accepts direct gateway class ownership');
arch3_assert(
    $validConstructor['found'] && arch3_has_direct_gateway_id_assignment($validConstructor['body']),
    'constructor guard accepts direct protected gateway ID assignment'
);

printf("\nArchitecture Bootstrap Paths: %d PASS / %d FAIL\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);
