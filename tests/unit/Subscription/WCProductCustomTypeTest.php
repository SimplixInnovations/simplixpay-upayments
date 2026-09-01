<?php

namespace Simplix\Pay\UPayments\Tests\Subscription;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class WCProductCustomTypeTest extends TestCase {
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_is_inert_when_woocommerce_simple_product_base_is_absent(): void {
        self::assertFalse(class_exists('WC_Product_Simple', false));

        require dirname(__DIR__, 3) . '/src/Subscription/WCProductCustomType.php';

        self::assertFalse(class_exists('WCProductCustomType', false));
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_declares_exact_global_child_and_product_type_when_base_exists(): void {
        require dirname(__DIR__, 2) . '/fixtures/subscription-product-type-base.php';

        require dirname(__DIR__, 3) . '/src/Subscription/WCProductCustomType.php';

        self::assertTrue(class_exists('WCProductCustomType', false));
        $reflection = new ReflectionClass('WCProductCustomType');
        self::assertSame('WC_Product_Simple', $reflection->getParentClass()->getName());
        self::assertSame('custom_type', (new \WCProductCustomType())->get_type());
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_preserves_a_preexisting_global_compatibility_class(): void {
        require dirname(__DIR__, 2) . '/fixtures/subscription-product-type-existing.php';

        require dirname(__DIR__, 3) . '/src/Subscription/WCProductCustomType.php';

        self::assertSame('existing_type', (new \WCProductCustomType())->get_type());
    }
}
