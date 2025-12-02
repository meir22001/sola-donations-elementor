jQuery(document).ready(function ($) {

    // --- Helper Functions ---

    function getWidgetData() {
        var $wrapper = $('.sola-donation-wrapper');
        if ($wrapper.length) {
            var configStr = $wrapper.attr('data-sola-config');
            var stylesStr = $wrapper.attr('data-sola-styles');
            return {
                config: configStr ? JSON.parse(configStr) : {},
                styles: stylesStr ? JSON.parse(stylesStr) : {}
            };
        }
        return { config: {}, styles: {} };
    }

    var widgetData = getWidgetData();
    var config = widgetData.config;
    var elementorStyles = widgetData.styles;

    // --- Initialization ---

    if (typeof sola_vars !== 'undefined' && sola_vars.ifields_key) {

        // Map Elementor Styles to Sola SDK Format
        var solaStyles = {
            body: {
                'font-family': elementorStyles.fontFamily || 'inherit',
                'font-size': elementorStyles.fontSize || '14px',
                'color': elementorStyles.textColor || '#333'
            },
            input: {
                'color': elementorStyles.textColor || '#333',
                'font-size': elementorStyles.fontSize || '14px'
            },
            '::placeholder': {
                'color': elementorStyles.placeholderColor || '#999'
            }
        };

        // Initialize Sola
        setAccount(sola_vars.ifields_key, 'sola-donations', '1.0');

        // Load iFields
        if ($('#sola-ifield-card-number').length) {
            loadIField('sola-ifield-card-number', 'card-number', solaStyles);
            loadIField('sola-ifield-exp', 'exp', solaStyles);
            loadIField('sola-ifield-cvv', 'cvv', solaStyles);
        }
    }

    // --- Event Listeners ---

    // Amount Selection
    $('.sola-amount-btn').on('click', function () {
        $('.sola-amount-btn').removeClass('selected');
        $(this).addClass('selected');
        $('.sola-custom-amount').val(''); // Clear custom amount
    });

    $('.sola-custom-amount').on('focus', function () {
        $('.sola-amount-btn').removeClass('selected');
    });

    // --- Submission Logic ---

    $('#sola-submit-btn').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var amount = getDonationAmount();

        if (!amount || amount <= 0) {
            alert('Please select or enter a valid donation amount.');
            return;
        }

        // Basic Validation for Required Fields
        var isValid = true;
        $('.sola-donor-info input[required]').each(function () {
            if ($(this).val() === '') {
                isValid = false;
                $(this).css('border-color', 'red');
            } else {
                $(this).css('border-color', '#ccc');
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields.');
            return;
        }

        $btn.prop('disabled', true).text('Processing...');
        $('#sola-message-container').html('');

        // Get Token from Sola
        getTokens(function () {
            // Success Callback
            var token = '';
            var data = arguments[0];

            if (data && data.xToken) {
                token = data.xToken;
            } else {
                // Fallback: Try to find it in the DOM
                try {
                    token = document.getElementById('sola-ifield-card-number').contentWindow.document.getElementById('token').value;
                } catch (e) {
                    console.log('Could not retrieve token from DOM');
                }
            }

            if (token) {
                processDonation(token);
            } else {
                $btn.prop('disabled', false).text('Donate Now');
                $('#sola-message-container').html('<p class="sola-error">Error: Could not retrieve payment token.</p>');
            }

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

    function getSelectedCurrency() {
        if ($('.sola-currency-select').length > 0) {
            return $('.sola-currency-select').val();
        }
        return config.currency || 'USD';
    }

    function processDonation(token) {
        var $btn = $('#sola-submit-btn');
        var amount = getDonationAmount();
        var currentCurrency = getSelectedCurrency();

        // Collect Donor Data
        var donorData = {
            first_name: $('input[name="first_name"]').val() || '',
            last_name: $('input[name="last_name"]').val() || '',
            email: $('input[name="email"]').val() || '',
            phone: $('input[name="phone"]').val() || '',
            address: $('input[name="address"]').val() || '',
            city: $('input[name="city"]').val() || '',
            zip: $('input[name="zip"]').val() || ''
        };

        // Recurring Logic
        var isRecurring = false;
        if (config.recurring_enabled === 'yes') {
            isRecurring = $('input[name="is_recurring"]').is(':checked');
        }

        $.ajax({
            url: sola_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'sola_process_donation',
                nonce: sola_vars.nonce,
                xToken: token,
                amount: amount,
                currency: currentCurrency,
                is_recurring: isRecurring,
                recurring_day: config.recurring_day || 1,
                donor_data: donorData
            },
            success: function (response) {
                if (response.success) {
                    $('#sola-message-container').html('<p class="sola-success">' + response.data.message + '</p>');
                    $btn.text('Donation Successful');

                    // Optional: Redirect
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
