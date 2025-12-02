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
        console.log('Sola Init Started (Tailwind Mode)', config);
    }

    // --- Initialization ---

    if (typeof sola_vars !== 'undefined' && sola_vars.ifields_key) {

        if (typeof setAccount === 'function') {
            try {
                setAccount(sola_vars.ifields_key, 'sola-donations', '1.0');

                // Tailwind Dark Mode Styles for iFields
                var fontStyle = {
                    'font-family': 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                    'font-size': '16px',
                    'color': '#ffffff' // White text
                };

                var inputStyle = {
                    'color': '#ffffff',
                    'font-size': '16px'
                };

                var placeholderStyle = {
                    'color': '#94a3b8' // Slate-400
                };

                setIfieldStyle('body', fontStyle);
                setIfieldStyle('input', inputStyle);
                setIfieldStyle('::placeholder', placeholderStyle);

                if (config.debug_mode) console.log('Sola: Dark mode styles applied.');

            } catch (e) {
                console.error('Sola SDK Init Failed:', e);
            }
        } else {
            console.error('Sola Error: setAccount function not found.');
        }

    } else {
        console.error('Sola Error: sola_vars or ifields_key missing.');
    }

    // --- UI Logic (Tailwind Classes) ---

    // Amount Selection
    $('.sola-amount-btn').on('click', function () {
        // Reset all buttons to default state
        $('.sola-amount-btn').removeClass('border-rose-400 bg-rose-400/10 text-rose-400 selected')
            .addClass('border-slate-700 text-slate-300');

        // Set selected button state
        $(this).removeClass('border-slate-700 text-slate-300')
            .addClass('border-rose-400 bg-rose-400/10 text-rose-400 selected');

        $('.sola-custom-amount').val(''); // Clear custom amount
    });

    $('.sola-custom-amount').on('focus', function () {
        // Deselect all buttons
        $('.sola-amount-btn').removeClass('border-rose-400 bg-rose-400/10 text-rose-400 selected')
            .addClass('border-slate-700 text-slate-300');
    });

    // Frequency Toggle
    $('.sola-frequency-btn').on('click', function () {
        var frequency = $(this).data('frequency');

        // Update hidden input
        $('#sola_is_recurring').val(frequency === 'monthly' ? '1' : '0');

        // Reset all buttons
        $('.sola-frequency-btn').removeClass('text-white bg-slate-700 shadow-sm')
            .addClass('text-slate-400 hover:text-white');

        // Set active button
        $(this).removeClass('text-slate-400 hover:text-white')
            .addClass('text-white bg-slate-700 shadow-sm');
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

        // Basic Validation
        var isValid = true;
        $('.sola-donor-info input[required]').each(function () {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('border-rose-500').removeClass('border-slate-700');
            } else {
                $(this).removeClass('border-rose-500').addClass('border-slate-700');
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields.');
            return;
        }

        $btn.prop('disabled', true).html('<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');
        $('#sola-message-container').html('');

        // Get Token from Sola
        if (typeof getTokens === 'function') {
            getTokens(function () {
                // Success Callback
                var token = '';
                var data = arguments[0];

                if (data && data.xToken) {
                    token = data.xToken;
                } else {
                    // Fallback for standard IDs
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
                    $('#sola-message-container').html('<p class="text-rose-400 mt-2">Error: Could not retrieve payment token.</p>');
                }

            }, function (data) {
                // Error Callback
                if (config.debug_mode) console.error('Sola Token Error:', data);
                $btn.prop('disabled', false).text('Donate Now');
                $('#sola-message-container').html('<p class="text-rose-400 mt-2">' + (data.errorMessage || 'Error generating token') + '</p>');
            });
        } else {
            console.error('Sola Error: getTokens function not found.');
            $btn.prop('disabled', false).text('Donate Now');
            $('#sola-message-container').html('<p class="text-rose-400 mt-2">Payment system error.</p>');
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
        if ($('#sola_currency_select').length > 0) {
            return $('#sola_currency_select').val();
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
        var isRecurring = $('#sola_is_recurring').val() === '1';

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
                    $('#sola-message-container').html('<p class="text-green-400 mt-2">' + response.data.message + '</p>');
                    $btn.text('Donation Successful');

                    if (response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    }
                } else {
                    $('#sola-message-container').html('<p class="text-rose-400 mt-2">' + response.data.message + '</p>');
                    $btn.prop('disabled', false).text('Donate Now');
                }
            },
            error: function () {
                $('#sola-message-container').html('<p class="text-rose-400 mt-2">Server error. Please try again.</p>');
                $btn.prop('disabled', false).text('Donate Now');
            }
        });
    }
});
