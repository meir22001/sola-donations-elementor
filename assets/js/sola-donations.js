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

    if (config.debug_mode) {
        console.log('Sola Init Started', config);
        console.log('Sola Styles', elementorStyles);
    }

    // --- Initialization ---

    if (typeof sola_vars !== 'undefined' && sola_vars.ifields_key) {

        // Wait for SDK to be ready if needed, or just init
        // Standard Sola SDK puts 'setAccount' on window.
        if (typeof setAccount === 'function') {
            try {
                if (config.debug_mode) console.log('Sola: Calling setAccount...');

                setAccount(sola_vars.ifields_key, 'sola-donations', '1.0');

                // Apply Styles using setIfieldStyle
                // Sola SDK expects specific style keys.
                // We map Elementor styles to Sola keys.

                var fontStyle = {
                    'font-family': elementorStyles.fontFamily || 'inherit',
                    'font-size': elementorStyles.fontSize || '14px',
                    'color': elementorStyles.textColor || '#333'
                };

                var inputStyle = {
                    'color': elementorStyles.textColor || '#333',
                    'font-size': elementorStyles.fontSize || '14px'
                };

                var placeholderStyle = {
                    'color': elementorStyles.placeholderColor || '#999'
                };

                // Apply to all fields
                // Note: Sola automatically finds iframes based on IDs if they exist, 
                // OR it injects them into divs with specific IDs.
                // The standard IDs are usually: 'ifields_card_number', 'ifields_expiration_date', 'ifields_cvv'
                // OR we can specify them.
                // Since we are using divs with IDs in the widget, Sola should find them if we use standard IDs.
                // If we use custom IDs, we might need to configure Sola.
                // However, `setAccount` initializes the library.
                // The library then looks for containers.

                // Let's assume we update the Widget to use standard IDs:
                // ifields_card_number, ifields_expiration_date, ifields_cvv

                setIfieldStyle('body', fontStyle);
                setIfieldStyle('input', inputStyle);
                setIfieldStyle('::placeholder', placeholderStyle);

                if (config.debug_mode) console.log('Sola: Styles applied.');

            } catch (e) {
                console.error('Sola SDK Init Failed:', e);
            }
        } else {
            console.error('Sola Error: setAccount function not found. SDK not loaded?');
        }

    } else {
        console.error('Sola Error: sola_vars or ifields_key missing.');
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

    // Currency Selection (Sync with hidden field if needed, or just read value)
    $('#sola_currency_select').on('change', function () {
        if (config.debug_mode) console.log('Currency changed to:', $(this).val());
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
        // Sola SDK `getTokens` function
        if (typeof getTokens === 'function') {
            getTokens(function () {
                // Success Callback
                var token = '';
                var data = arguments[0];

                if (config.debug_mode) console.log('Sola Token Success:', data);

                if (data && data.xToken) {
                    token = data.xToken;
                } else {
                    // Fallback: Try to find it in the DOM (Standard ID)
                    try {
                        token = document.getElementById('ifields_card_number').contentWindow.document.getElementById('token').value;
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
                if (config.debug_mode) console.error('Sola Token Error:', data);
                $btn.prop('disabled', false).text('Donate Now');
                $('#sola-message-container').html('<p class="sola-error">' + (data.errorMessage || 'Error generating token') + '</p>');
            });
        } else {
            console.error('Sola Error: getTokens function not found.');
            $btn.prop('disabled', false).text('Donate Now');
            $('#sola-message-container').html('<p class="sola-error">Payment system error.</p>');
        }
    });

    function getDonationAmount() {
        if ($('.sola-amount-btn.selected').length > 0) {
            return $('.sola-amount-btn.selected').data('amount');
        } else {
            return $('.sola-custom-amount').val();
        }
    }

    function getSelectedCurrency() {
        // Check for explicit select first
        if ($('#sola_currency_select').length > 0) {
            return $('#sola_currency_select').val();
        }
        // Fallback to class selector
        if ($('.sola-currency-select').length > 0) {
            return $('.sola-currency-select').val();
        }
        return config.currency || 'USD';
    }

    function processDonation(token) {
        var $btn = $('#sola-submit-btn');
        var amount = getDonationAmount();
        var currentCurrency = getSelectedCurrency();

        if (config.debug_mode) console.log('Processing Donation:', { amount: amount, currency: currentCurrency });

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
