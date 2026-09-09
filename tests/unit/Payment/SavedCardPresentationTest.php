<?php

namespace Simplixi\SUPCheckout\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplixi\SUPCheckout\Payment\SavedCardPresentation;

final class SavedCardPresentationTest extends TestCase {
    public function test_prefers_only_valid_explicit_last_four_over_provider_number(): void {
        self::assertSame('4242', SavedCardPresentation::last_four(array(
            'last4'  => '4242',
            'number' => '5555 5555 5555 4444',
        )));
        self::assertSame('4242', SavedCardPresentation::last_four(array(
            'last4' => '•••• 4242',
        )));

        self::assertSame('4444', SavedCardPresentation::last_four(array(
            'last4'  => '4111 1111 1111 4242',
            'number' => '5555 5555 5555 4444',
        )));
        self::assertSame('', SavedCardPresentation::last_four(array(
            'last4' => '4111 1111 1111 4242',
        )));
    }

    public function test_derives_only_final_four_digits_from_provider_number(): void {
        self::assertSame('4242', SavedCardPresentation::last_four(array(
            'number' => '4242 4242-4242 4242',
        )));
        self::assertSame('4242', SavedCardPresentation::last_four(array(
            'number' => '************4242',
        )));
    }

    public function test_malformed_or_short_card_shapes_fail_to_generic_presentation(): void {
        self::assertSame('', SavedCardPresentation::last_four(array()));
        self::assertSame('', SavedCardPresentation::last_four(array('number' => '12')));
        self::assertSame('', SavedCardPresentation::last_four(array('last4' => array('4242'))));
        self::assertSame('', SavedCardPresentation::last_four(array('number' => new \stdClass())));
    }

    public function test_display_label_never_contains_more_than_last_four_digits(): void {
        $raw = '4111 1111 1111 4242';
        $label = SavedCardPresentation::label(array('number' => $raw));

        self::assertSame('•••• 4242', $label);
        self::assertStringNotContainsString($raw, $label);
        self::assertStringNotContainsString('4111', $label);
        self::assertStringNotContainsString('1111', $label);
    }

    public function test_display_label_is_generic_when_last_four_is_unavailable(): void {
        self::assertSame('Saved card', SavedCardPresentation::label(array('number' => 'x')));
    }

    public function test_blocks_card_contract_never_contains_provider_number_or_last4(): void {
        $raw = '4111 1111 1111 4242';
        $card = SavedCardPresentation::for_blocks(array(
            'token'  => 'card-token',
            'last4'  => $raw,
            'number' => $raw,
            'brand'  => 'Visa',
        ));

        self::assertSame(array(
            'token' => 'card-token',
            'label' => '•••• 4242',
            'brand' => 'Visa',
        ), $card);
        self::assertArrayNotHasKey('number', $card);
        self::assertArrayNotHasKey('last4', $card);
        self::assertStringNotContainsString('4111', $card['label']);
    }

    public function test_blocks_brand_is_short_digit_free_provider_text_only(): void {
        self::assertSame('Visa', SavedCardPresentation::safe_brand(array('brand' => ' Visa ')));
        self::assertSame('American Express', SavedCardPresentation::safe_brand(array('brand' => 'American Express')));
        self::assertSame('', SavedCardPresentation::safe_brand(array('brand' => 'Visa 4111 1111 1111 4242')));
        self::assertSame('', SavedCardPresentation::safe_brand(array('brand' => 1234)));
        self::assertSame('', SavedCardPresentation::safe_brand(array('brand' => str_repeat('V', 33))));
    }

    public function test_blocks_card_rejects_non_string_or_blank_tokens(): void {
        self::assertNull(SavedCardPresentation::for_blocks(array('token' => 1234, 'number' => '4242')));
        self::assertNull(SavedCardPresentation::for_blocks(array('token' => '', 'number' => '4242')));
        self::assertNull(SavedCardPresentation::for_blocks(array('number' => '4242')));
    }
}
