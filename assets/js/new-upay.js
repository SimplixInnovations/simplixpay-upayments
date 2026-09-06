(function ($, window, document) {
    'use strict';

    const api = window.suCheckoutUpayments = window.suCheckoutUpayments || {};

    function hidePlaceOrderButtonIfNeeded() {
        const selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
        if (selectedPaymentMethod === 'upayments') {
            $('button#place_order').hide();
        } else {
            $('button#place_order').show();
        }
    }

    function checkApplePayAvailability() {
        const applePay = {
            supportedByDevice: function () {
                return 'ApplePaySession' in window;
            },
            getMerchantIdentifier: function () {
                return 'merchant.com.upayments.ustore';
            }
        };

        const merchantIdentifier = applePay.getMerchantIdentifier();
        if (!merchantIdentifier || !applePay.supportedByDevice()) {
            return;
        }

        if (window.ApplePaySession.canMakePayments()) {
            return;
        }

        window.ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier).catch(function () {
            // Availability probing is advisory only; checkout remains provider-driven.
        });
    }

    api.submitPaymentMethod = function (buttonValue) {
        $('#upayment_payment_type').val(buttonValue);
        $('#card_token').val('');
        if (buttonValue !== 'cc') {
            $('#save_card').val('0');
            const checkbox = document.getElementById('chkSaveCard');
            if (checkbox) {
                checkbox.checked = false;
            }
        }
        $('form.checkout').submit();
    };

    api.submitSavedCard = function (button) {
        $('#upayment_payment_type').val('cc');
        $('#card_token').val(button.value);
        $('#save_card').val('0');
        const checkbox = document.getElementById('chkSaveCard');
        if (checkbox) {
            checkbox.checked = false;
        }
        $('form.checkout').submit();
    };

    api.toggleSaveCard = function (loggedUser) {
        const checkbox = document.getElementById('chkSaveCard');
        const saveCardInput = $('#save_card');

        if (loggedUser === false || !checkbox) {
            if (checkbox) {
                checkbox.checked = false;
            }
            saveCardInput.val('0');
            if (loggedUser === false) {
                api.showToast('Please log in to save or use a saved card.', 3000);
            }
            return;
        }

        saveCardInput.val(checkbox.checked ? '1' : '0');
    };

    api.showToast = function (message, duration) {
        const toast = document.getElementById('wc-toast');
        if (!toast) {
            return;
        }

        toast.textContent = String(message);
        toast.classList.add('show');

        window.setTimeout(function () {
            toast.classList.remove('show');
        }, typeof duration === 'number' ? duration : 3000);
    };

    $(function () {
        function refreshCheckoutUi() {
            hidePlaceOrderButtonIfNeeded();
            checkApplePayAvailability();
        }

        $('form.checkout').on('change', 'input[name="payment_method"]', refreshCheckoutUi);

        refreshCheckoutUi();
        window.setTimeout(refreshCheckoutUi, 500);

        $(document).ajaxComplete(refreshCheckoutUi);

        const paymentMethodId = 'upayments';
        if ($('form.checkout').length > 0 && $('input[name="payment_method"]:checked').val() !== paymentMethodId) {
            $('input[name="payment_method"][value="' + paymentMethodId + '"]').trigger('click');
        }
    });
})(jQuery, window, document);
