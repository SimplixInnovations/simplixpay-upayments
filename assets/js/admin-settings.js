jQuery(function ($) {
    'use strict';

    const $newDesignCheckbox = $('#woocommerce_upayments_use_new_design');
    const $saveCardCheckbox = $('#woocommerce_upayments_enable_save_card');
    const $multiMerchantCheckbox = $('#woocommerce_upayments_enable_multimerchant');
    const $multiMerchantRow = $('.upayments-multimerchant-repeater').closest('tr');

    // Fail closed if this script is ever evaluated outside the exact gateway settings DOM.
    if (
        !$newDesignCheckbox.length
        || !$saveCardCheckbox.length
        || !$multiMerchantCheckbox.length
        || !$multiMerchantRow.length
    ) {
        return;
    }

    const $saveCardRow = $saveCardCheckbox.closest('tr');

    function toggleSaveCardState() {
        const originalChecked = $saveCardCheckbox.data('original-checked');

        if ($newDesignCheckbox.is(':checked')) {
            $saveCardCheckbox.prop('disabled', false);
            if (typeof originalChecked !== 'undefined') {
                $saveCardCheckbox.prop('checked', originalChecked);
            }
            $saveCardRow.removeClass('upayments-disabled-setting');
            return;
        }

        if (typeof originalChecked === 'undefined') {
            $saveCardCheckbox.data('original-checked', $saveCardCheckbox.prop('checked'));
        }

        $saveCardCheckbox.prop('disabled', true).prop('checked', false);
        $saveCardRow.addClass('upayments-disabled-setting');
    }

    function toggleMultiMerchantState() {
        $multiMerchantRow.toggle($multiMerchantCheckbox.is(':checked'));
    }

    toggleSaveCardState();
    toggleMultiMerchantState();

    $newDesignCheckbox.on('change', toggleSaveCardState);
    $multiMerchantCheckbox.on('change', toggleMultiMerchantState);
});
