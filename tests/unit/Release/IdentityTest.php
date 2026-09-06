<?php

namespace Simplix\Pay\UPayments\Tests\Release;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Simplix\Pay\UPayments\Release\Identity;

final class IdentityTest extends TestCase {
    public function test_canonical_public_brand_and_repository_are_exact(): void {
        self::assertSame('SUCheckout for UPayments', Identity::PRODUCT_NAME);
        self::assertSame('SUCheckout', Identity::SHORT_NAME);
        self::assertSame('sucheckout-upayments', Identity::SLUG);
        self::assertSame('SimplixInnovations/sucheckout-upayments', Identity::REPOSITORY);
    }

    public function test_version_remains_an_independent_pre_one_release(): void {
        self::assertMatchesRegularExpression('/\A0\.[0-9]+\.[0-9]+(?:[-+][0-9A-Za-z.-]+)?\z/', Identity::VERSION);
        self::assertSame('0.1.0', Identity::VERSION);
    }

    public function test_external_update_channel_is_explicitly_disabled(): void {
        self::assertSame('disabled', Identity::UPDATE_CHANNEL);
    }

    public function test_historical_install_identities_remain_exact(): void {
        self::assertSame('UPayments.php', Identity::LEGACY_MAIN_FILE);
        self::assertSame('upayments', Identity::LEGACY_TEXT_DOMAIN);
    }

    public function test_future_targets_are_frozen_but_not_current_identity(): void {
        self::assertSame('sucheckout-upayments.php', Identity::TARGET_MAIN_FILE);
        self::assertSame('sucheckout-upayments', Identity::TARGET_TEXT_DOMAIN);
        self::assertNotSame(Identity::LEGACY_MAIN_FILE, Identity::TARGET_MAIN_FILE);
        self::assertNotSame(Identity::LEGACY_TEXT_DOMAIN, Identity::TARGET_TEXT_DOMAIN);
    }

    public function test_identity_is_a_non_instantiable_constant_boundary(): void {
        $reflection = new ReflectionClass(Identity::class);
        self::assertTrue($reflection->isFinal());
        self::assertNotNull($reflection->getConstructor());
        self::assertTrue($reflection->getConstructor()->isPrivate());
    }
}
