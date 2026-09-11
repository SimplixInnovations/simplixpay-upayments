from pathlib import Path

p = Path('tests/integration/CheckoutValidationBoundaryRuntimeTest.php')
s = p.read_text()
old = """try {
    WC()->shipping()->reset_shipping();
    WC()->cart->calculate_totals();
    $packages = WC()->shipping()->get_packages();
    supcheckout_cert_assert(! empty($packages), 'physical cart produces at least one Woo shipping package');
    foreach ($packages as $package) {
        supcheckout_cert_assert(isset($package['rates']) && array() === $package['rates'], 'certification filter leaves the Woo shipping package with no available rate');
    }
    supcheckout_cert_assert(true === WC()->cart->needs_shipping(), 'physical cart requires shipping before payment');
    supcheckout_cert_assert(false === WC()->cart->needs_payment(), 'payment validation is isolated out of this shipping-boundary probe');
"""
new = """$shipping = WC()->shipping();
$shipping_enabled_before = $shipping->enabled;
$ship_to_countries_before = get_option('woocommerce_ship_to_countries', '');
$default_zone = WC_Shipping_Zones::get_zone(0);
$shipping_instance_id = $default_zone->add_shipping_method('flat_rate');

try {
    supcheckout_cert_assert(is_int($shipping_instance_id) && $shipping_instance_id > 0, 'certification store configures one real Woo shipping method');
    update_option('woocommerce_ship_to_countries', 'all');
    $shipping->enabled = true;
    $shipping->reset_shipping();
    // Woo core caches this count independently. Mirror Woo's own tests and
    // invalidate the shipping transient after changing zone methods.
    WC_Cache_Helper::get_transient_version('shipping', true);
    delete_transient('wc_shipping_method_count');

    supcheckout_cert_assert(wc_get_shipping_method_count(true) > 0, 'Woo reports at least one configured shipping method');
    supcheckout_cert_assert(true === WC()->cart->needs_shipping(), 'physical cart requires shipping before payment');
    $raw_packages = WC()->cart->get_shipping_packages();
    supcheckout_cert_assert(! empty($raw_packages), 'physical cart produces at least one raw Woo shipping package');

    $packages = $shipping->calculate_shipping($raw_packages);
    supcheckout_cert_assert(! empty($packages), 'Woo shipping engine calculates the physical cart package');
    foreach ($packages as $package) {
        supcheckout_cert_assert(isset($package['rates']) && array() === $package['rates'], 'certification filter leaves the Woo shipping package with no available rate');
    }
    supcheckout_cert_assert(false === WC()->cart->needs_payment(), 'payment validation is isolated out of this shipping-boundary probe');
"""
if s.count(old) != 1:
    raise SystemExit(f'expected one shipping-boundary fixture block, found {s.count(old)}')
s = s.replace(old, new, 1)
old_finally = """} finally {
    remove_filter('woocommerce_package_rates', $force_no_rates, PHP_INT_MAX);
"""
new_finally = """} finally {
    if (is_int($shipping_instance_id) && $shipping_instance_id > 0) {
        $default_zone->delete_shipping_method($shipping_instance_id);
    }
    WC_Cache_Helper::get_transient_version('shipping', true);
    delete_transient('wc_shipping_method_count');
    update_option('woocommerce_ship_to_countries', $ship_to_countries_before);
    $shipping->enabled = $shipping_enabled_before;
    $shipping->reset_shipping();
    remove_filter('woocommerce_package_rates', $force_no_rates, PHP_INT_MAX);
"""
if s.count(old_finally) != 1:
    raise SystemExit(f'expected one shipping-boundary finally block, found {s.count(old_finally)}')
p.write_text(s.replace(old_finally, new_finally, 1))
