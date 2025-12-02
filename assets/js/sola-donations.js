jQuery(document).ready(function ($) {

    // Helper to get config
    function getWidgetConfig() {
        var $wrapper = $('.sola-donation-wrapper');
        if ($wrapper.length) {
            var configStr = $wrapper.attr('data-sola-config');
            if (configStr) {
                return JSON.parse(configStr);
            }
        }
        return {};
    }

    var config = getWidgetConfig();

    // Initialize Sola iFields
    if (typeof sola_vars !== 'undefined' && sola_vars.ifields_key) {
        setAccount(sola_vars.ifields_key, 'sola-donations', '1.0');

        // Define iFields styles from config
        var style = {
            'font-family': 'Helvetica, sans-serif',
            'font-size': '14px',
            'color': '#333'
        };

        if (config.ifields_styles) {
            style = config.ifields_styles;
        }

        // Load iFields
        loadIField('sola-ifield-card-number', 'card-number', style);
        loadIField('sola-ifield-exp', 'exp', style);
        loadIField('sola-ifield-cvv', 'cvv', style);
    }

    // Amount Selection
    $('.sola-amount-btn').on('click', function () {
        $('.sola-amount-btn').removeClass('selected');
        $(this).addClass('selected');

        var amount = $(this).data('amount');
        $('.sola-custom-amount').val(''); // Clear custom amount
    });

    $('.sola-custom-amount').on('focus', function () {
        $('.sola-amount-btn').removeClass('selected');
    });

    // Wallet Buttons Logic
    if (config.enable_wallets === 'yes') {
        $('#sola-google-pay-btn').on('click', function () {
            initiateWalletPayment('google_pay');
        });
        $('#sola-apple-pay-btn').on('click', function () {
            initiateWalletPayment('apple_pay');
        });
    }

    function initiateWalletPayment(walletType) {
        // Placeholder for Sola Wallet Flow
        // Real implementation would involve:
        // 1. Check if wallet is available (using Sola SDK)
        // 2. Trigger payment sheet
        // 3. On success, get token and call processDonation(token, 'wallet')

        alert('Wallet payment (' + walletType + ') initiated. (Simulation)');
        // Simulate success for now
        // processDonation({ xToken: 'simulated_wallet_token' }, true);
    }

    // Form Submission
    $('#sola-submit-btn').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var amount = getDonationAmount();

        if (!amount || amount <= 0) {
            alert('Please select or enter a valid donation amount.');
            return;
        }

        $btn.prop('disabled', true).text('Processing...');
        $('#sola-message-container').html('');

        // Get Token
        getTokens(function () {
            // Success Callback - token is in the hidden field inside iframe, 
            // but standard Sola flow usually returns data in callback or we access it.
            // Let's assume we get the token via SDK method or it's auto-injected.
            // For this implementation, we'll assume the token is retrieved via SDK helper or DOM.

            // In a real Sola integration, getTokens usually doesn't return the token directly in the callback args 
            // in all versions, but let's assume standard behavior:
            var token = document.getElementById('sola-ifield-card-number').contentWindow.document.getElementById('token').value;

            processDonation({ xToken: token }, false);

        }, function (data) {
            // Error Callback
            $btn.prop('disabled', false).text('Donate Now');
            $('#sola-message-container').html('<p class="sola-error">' + (data.errorMessage || 'Error generating token') + '</p>');
        });
    });

    function getDonationAmount() {
        if ($('.sola-amount-btn.selected').length > 0) {
            return $('.sola-amount-btn.selected').data('amount');
        } else {
            return $('.sola-custom-amount').val();
        }
    }

    function processDonation(data, isWallet) {
        var $btn = $('#sola-submit-btn');
        var amount = getDonationAmount();

        // Recurring Logic
        var isRecurring = false;
        var recurringDay = config.recurring_day || 1;
        var chargeImmediately = config.charge_immediately === 'yes';

        if (config.donation_type === 'recurring') {
            isRecurring = true;
        } else if (config.donation_type === 'both') {
            isRecurring = $('input[name="is_recurring"]').is(':checked');
        }

        $.ajax({
            url: sola_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'sola_process_donation',
                nonce: sola_vars.nonce,
                xToken: data.xToken,
                amount: amount,
                is_recurring: isRecurring,
                recurring_day: recurringDay,
                charge_immediately: chargeImmediately,
                currency: config.currency,
                webhook_url: config.webhook === 'yes' ? config.webhook_url : '',
                admin_email: config.email_admin === 'yes' ? config.admin_email : '',
                redirect_url: config.redirect === 'yes' ? config.redirect_url : ''
            },
            success: function (response) {
                if (response.success) {
                    $('#sola-message-container').html('<p class="sola-success">' + response.data.message + '</p>');
                    $btn.text('Donation Successful');

                    // Post-Success Actions
                    if (response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    }
                } else {
                    $('#sola-message-container').html('<p class="sola-error">' + response.data.message + '</p>');
                    $btn.prop('disabled', false).text('Donate Now');
                }
            },
            error: function () {
                $('#sola-message-container').html('<p class="sola-error">Server error. Please try again.</p>');
                $btn.prop('disabled', false).text('Donate Now');
            }
        });
    }
});
