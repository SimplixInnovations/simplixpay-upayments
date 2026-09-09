<?php

namespace Simplixi\SUPCheckout\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplixi\SUPCheckout\Payment\SavedCardSelection;

final class SavedCardSelectionTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        supcheckout_test_reset_wp_options();
        $GLOBALS['supcheckout_test_wp_salt'] = 'saved-card-selection-test-salt';
    }

    public function test_handle_is_opaque_deterministic_and_bound_to_context(): void {
        $provider_token = 'provider-card-token-ABC_123';
        $handle = SavedCardSelection::create($provider_token, 42, 'merchant-api-key', false);

        self::assertIsString($handle);
        self::assertMatchesRegularExpression('/^sc1_[0-9a-f]{64}$/D', $handle);
        self::assertStringNotContainsString($provider_token, $handle);
        self::assertSame($handle, SavedCardSelection::create($provider_token, 42, 'merchant-api-key', false));

        self::assertNotSame($handle, SavedCardSelection::create($provider_token, 43, 'merchant-api-key', false));
        self::assertNotSame($handle, SavedCardSelection::create($provider_token, 42, 'other-api-key', false));
        self::assertNotSame($handle, SavedCardSelection::create($provider_token, 42, 'merchant-api-key', true));
        self::assertNotSame($handle, SavedCardSelection::create('other-provider-token', 42, 'merchant-api-key', false));

        $GLOBALS['supcheckout_test_wp_salt'] = 'rotated-saved-card-selection-test-salt';
        self::assertNotSame($handle, SavedCardSelection::create($provider_token, 42, 'merchant-api-key', false));
    }

    public function test_create_fails_closed_on_malformed_context_or_token(): void {
        self::assertNull(SavedCardSelection::create('', 42, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::create(' provider-token ', 42, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::create('provider-token', 0, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::create('provider-token', -1, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::create('provider-token', 42, '', false));

        $GLOBALS['supcheckout_test_wp_salt'] = '';
        self::assertNull(SavedCardSelection::create('provider-token', 42, 'merchant-api-key', false));
    }

    public function test_resolve_rebinds_handle_to_fresh_provider_cards(): void {
        $cards = array(
            array('token' => 'provider-card-A', 'number' => '••••1111'),
            array('token' => 'provider-card-B', 'number' => '••••2222'),
        );
        $handle = SavedCardSelection::create('provider-card-B', 42, 'merchant-api-key', false);

        self::assertSame(
            'provider-card-B',
            SavedCardSelection::resolve($handle, $cards, 42, 'merchant-api-key', false)
        );

        self::assertNull(SavedCardSelection::resolve($handle, array_reverse($cards), 43, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::resolve($handle, array($cards[0]), 42, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::resolve('provider-card-B', $cards, 42, 'merchant-api-key', false));
        self::assertNull(SavedCardSelection::resolve('sc1_' . str_repeat('g', 64), $cards, 42, 'merchant-api-key', false));
    }

    public function test_resolve_ignores_malformed_provider_card_entries(): void {
        $valid = 'provider-card-valid';
        $handle = SavedCardSelection::create($valid, 42, 'merchant-api-key', false);
        $cards = array(
            'not-an-array',
            array(),
            array('token' => 12345678),
            array('token' => true),
            array('token' => ' provider-card-invalid '),
            array('token' => $valid),
        );

        self::assertSame($valid, SavedCardSelection::resolve($handle, $cards, 42, 'merchant-api-key', false));
    }

    public function test_submission_resolution_uses_handle_then_exact_legacy_membership(): void {
        $cards = array(
            array('token' => 'provider-card-A'),
            array('token' => 'provider-card-B'),
        );
        $handle = SavedCardSelection::create('provider-card-B', 42, 'merchant-api-key', false);

        self::assertSame(
            'provider-card-B',
            SavedCardSelection::resolve_submission($handle, $cards, 42, 'merchant-api-key', false)
        );
        self::assertSame(
            'provider-card-A',
            SavedCardSelection::resolve_submission('provider-card-A', $cards, 42, 'merchant-api-key', false)
        );
        self::assertNull(
            SavedCardSelection::resolve_submission('foreign-provider-card', $cards, 42, 'merchant-api-key', false)
        );

        $handle_shaped_provider_token = 'sc1_' . str_repeat('a', 64);
        self::assertSame(
            $handle_shaped_provider_token,
            SavedCardSelection::resolve_submission(
                $handle_shaped_provider_token,
                array(array('token' => $handle_shaped_provider_token)),
                42,
                'merchant-api-key',
                false
            )
        );
    }

    public function test_is_handle_accepts_only_canonical_handle_shape(): void {
        $handle = SavedCardSelection::create('provider-card-token', 42, 'merchant-api-key', false);

        self::assertTrue(SavedCardSelection::is_handle($handle));
        self::assertFalse(SavedCardSelection::is_handle('provider-card-token'));
        self::assertFalse(SavedCardSelection::is_handle('SC1_' . str_repeat('a', 64)));
        self::assertFalse(SavedCardSelection::is_handle('sc1_' . str_repeat('a', 63)));
        self::assertFalse(SavedCardSelection::is_handle(null));
        self::assertFalse(SavedCardSelection::is_handle(123));
    }
}
