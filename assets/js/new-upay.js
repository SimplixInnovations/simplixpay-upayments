(function ($, window, document) {
    'use strict';

    const api = window.supCheckout = window.supCheckout || {};

    function syncPlaceOrderButton() {
        const selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
        if (selectedPaymentMethod === 'upayments') {
            $('button#place_order').hide();
        } else {
            $('button#place_order').show();
        }
    }

    function submitThroughWooCheckout() {
        const placeOrder = $('button#place_order');

        if (placeOrder.length) {
            if (placeOrder.prop('disabled')) {
                return false;
            }

            placeOrder.trigger('click');
            return true;
        }

        $('form.checkout').trigger('submit');
        return true;
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
        submitThroughWooCheckout();
    };

    api.submitSavedCard = function (button) {
        $('#upayment_payment_type').val('cc');
        $('#card_token').val(button.value);
        $('#save_card').val('0');
        const checkbox = document.getElementById('chkSaveCard');
        if (checkbox) {
            checkbox.checked = false;
        }
        submitThroughWooCheckout();
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
        const $checkoutForm = $('form.checkout');
        $checkoutForm
            .off('change.supcheckoutPaymentLifecycle', 'input[name="payment_method"]')
            .on('change.supcheckoutPaymentLifecycle', 'input[name="payment_method"]', syncPlaceOrderButton);
        $(document.body)
            .off('updated_checkout.supcheckoutPaymentLifecycle')
            .on('updated_checkout.supcheckoutPaymentLifecycle', syncPlaceOrderButton);
        syncPlaceOrderButton();
    });
})(jQuery, window, document);
