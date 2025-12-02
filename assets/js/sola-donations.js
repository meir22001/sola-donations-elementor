jQuery(document).ready(function ($) {

    // Initialize Sola iFields
    if (typeof sola_vars !== 'undefined' && sola_vars.ifields_key) {
        setAccount(sola_vars.ifields_key, 'sola-donations', '1.0');

        // Define iFields styles
        var style = {
            'font-family': 'Helvetica, sans-serif',
            'font-size': '14px',
            'color': '#333'
        };

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

        // Update hidden input or state if needed
        console.log('Selected Amount:', amount);
    });

    $('.sola-custom-amount').on('focus', function () {
        $('.sola-amount-btn').removeClass('selected');
    });

    // Form Submission
    $('#sola-submit-btn').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var amount = 0;

        // Get amount
        if ($('.sola-amount-btn.selected').length > 0) {
            amount = $('.sola-amount-btn.selected').data('amount');
        } else {
            amount = $('.sola-custom-amount').val();
        }

        if (!amount || amount <= 0) {
            alert('Please select or enter a valid donation amount.');
            return;
        }

        $btn.prop('disabled', true).text('Processing...');
        $('#sola-message-container').html('');

        // Get Token
        getTokens(function () {
            // Success Callback
            var tokenData = document.getElementById('sola-ifield-card-number').contentWindow.document.getElementById('token').value;
            // Note: In real iFields, getTokens populates hidden inputs or we access via SDK. 
            // The standard way is often just calling getTokens and it returns data or we access it.
            // Let's assume standard behavior: getTokens(onSuccess, onError)

            // Actually, standard iFields usage:
            // getTokens(function(tokens) { ... }, function(errors) { ... })

        }, function () {
            // Error Callback
            $btn.prop('disabled', false).text('Donate Now');
            $('#sola-message-container').html('<p class="sola-error">Error generating token. Please check your card details.</p>');
        });
    });

    // Override getTokens to match actual SDK usage if needed. 
    // Standard Sola/Cardknox iFields:
    // getTokens(onSuccess, onError)
    // onSuccess receives data object.
});

// Define callbacks for iFields
function onGetTokenSuccess(data) {
    var $ = jQuery;
    var $btn = $('#sola-submit-btn');

    var amount = 0;
    if ($('.sola-amount-btn.selected').length > 0) {
        amount = $('.sola-amount-btn.selected').data('amount');
    } else {
        amount = $('.sola-custom-amount').val();
    }

    var isRecurring = $('input[name="is_recurring"]').is(':checked');

    // Send to backend
    $.ajax({
        url: sola_vars.ajax_url,
        type: 'POST',
        data: {
            action: 'sola_process_donation',
            nonce: sola_vars.nonce,
            xToken: data.xToken,
            amount: amount,
            is_recurring: isRecurring
        },
        success: function (response) {
            if (response.success) {
                $('#sola-message-container').html('<p class="sola-success">' + response.data.message + '</p>');
                $btn.text('Donation Successful');
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

function onGetTokenError(data) {
    var $ = jQuery;
    $('#sola-submit-btn').prop('disabled', false).text('Donate Now');
    $('#sola-message-container').html('<p class="sola-error">' + data.errorMessage + '</p>');
}

// Overwrite the click handler to use the named callbacks
jQuery(document).ready(function ($) {
    $('#sola-submit-btn').off('click').on('click', function (e) {
        e.preventDefault();

        var amount = 0;
        if ($('.sola-amount-btn.selected').length > 0) {
            amount = $('.sola-amount-btn.selected').data('amount');
        } else {
            amount = $('.sola-custom-amount').val();
        }

        if (!amount || amount <= 0) {
            alert('Please select or enter a valid donation amount.');
            return;
        }

        $(this).prop('disabled', true).text('Processing...');
        $('#sola-message-container').html('');

        getTokens(onGetTokenSuccess, onGetTokenError);
    });
});
