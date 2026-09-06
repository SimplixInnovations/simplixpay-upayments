<?php

namespace Simplixi\SUCheckout\UPayments\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Simplixi\SUCheckout\UPayments\Provider\EndpointResolver;

final class EndpointResolverTest extends TestCase {
    public function test_live_and_sandbox_bases_remain_byte_exact(): void {
        self::assertSame(
            'https://apiv2api.upayments.com/api/v1/charge',
            (new EndpointResolver(false))->resolve('charge')
        );
        self::assertSame(
            'https://sandboxapi.upayments.com/api/v1/charge',
            (new EndpointResolver(true))->resolve('charge')
        );
    }

    public function test_fixed_routes_preserve_the_provider_contract(): void {
        $resolver = new EndpointResolver(false);

        self::assertSame(
            EndpointResolver::LIVE_BASE . EndpointResolver::CREATE_CUSTOMER_TOKEN,
            $resolver->create_customer_token()
        );
        self::assertSame(
            EndpointResolver::LIVE_BASE . EndpointResolver::CHECK_PAYMENT_BUTTON_STATUS,
            $resolver->check_payment_button_status()
        );
        self::assertSame(
            EndpointResolver::LIVE_BASE . EndpointResolver::RETRIEVE_CUSTOMER_CARDS,
            $resolver->retrieve_customer_cards()
        );
    }
}
