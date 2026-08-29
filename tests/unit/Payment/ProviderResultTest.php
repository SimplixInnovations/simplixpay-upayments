<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\ProviderResult;

final class ProviderResultTest extends TestCase {
    public function test_only_exact_documented_results_receive_terminal_meaning(): void {
        self::assertSame(ProviderResult::CAPTURED, ProviderResult::classify('CAPTURED'));
        self::assertSame(ProviderResult::PENDING, ProviderResult::classify('AUTHORIZED'));
        self::assertSame(ProviderResult::FAILED, ProviderResult::classify('NOT CAPTURED'));
        self::assertSame(ProviderResult::CANCELLED, ProviderResult::classify('CANCELED'));
    }

    public function test_unknown_or_malformed_results_remain_indeterminate(): void {
        self::assertSame(ProviderResult::INDETERMINATE, ProviderResult::classify('Processing'));
        self::assertSame(ProviderResult::INDETERMINATE, ProviderResult::classify('REFUND'));
        self::assertSame(ProviderResult::INDETERMINATE, ProviderResult::classify(''));
        self::assertSame(ProviderResult::INDETERMINATE, ProviderResult::classify(null));
        self::assertSame(ProviderResult::INDETERMINATE, ProviderResult::classify(array('CAPTURED')));
    }
}
