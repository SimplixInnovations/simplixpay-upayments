<?php

namespace Simplixi\SUPCheckout\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplixi\SUPCheckout\Payment\SavedCardPresentation;

final class SavedCardPresentationTest extends TestCase {
    public function test_prefers_explicit_last_four_over_provider_number(): void {
        self::assertSame('4242', SavedCardPresentation::last_four(array(
            'last4'  => '4242',
            'number' => '5555 5555 5555 4444',
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

    public function test_saved_card_template_does_not_echo_provider_number_into_presentation_attributes(): void {
        $template = file_get_contents(dirname(__DIR__, 3) . '/templates/new-design-form.php');

        self::assertIsString($template);
        self::assertStringContainsString('SavedCardPresentation::label', $template);
        self::assertStringNotContainsString('esc_html($card_number_raw)', $template);
        self::assertStringNotContainsString('esc_attr($card_number_raw)', $template);
        self::assertStringNotContainsString('title="<?php echo esc_attr($card_number_raw); ?>"', $template);
    }
}
