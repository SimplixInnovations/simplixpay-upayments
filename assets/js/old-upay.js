(function ($, window) {
    'use strict';

    function checkApplePayAvailability() {
        if (!('ApplePaySession' in window)) {
            return;
        }

        const merchantIdentifier = 'merchant.com.upayments.ustore';
        if (window.ApplePaySession.canMakePayments()) {
            return;
        }

        window.ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier).catch(function () {
            // Availability probing is advisory only; checkout remains provider-driven.
        });
    }

    $(function () {
        checkApplePayAvailability();
        window.setTimeout(checkApplePayAvailability, 500);
        $(document).ajaxComplete(checkApplePayAvailability);
    });
})(jQuery, window);
