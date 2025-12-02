document.addEventListener('DOMContentLoaded', function () {

    // State
    const state = {
        amount: 180,
        currency: 'ILS',
        frequency: 'onetime',
        lang: 'he'
    };

    // Translations
    const translations = {
        he: {
            formTitle: 'טופס תרומה לדוגמא',
            formSubtitle: 'התרומה שלך עוזרת לנו להמשיך בעשייה. כל תרומה, קטנה כגדולה, מצטרפת לשינוי הגדול.',
            donationDetails: 'פרטי התרומה',
            oneTime: 'תרומה חד פעמית',
            monthly: 'הוראת קבע חודשית',
            chargeDay: 'יום לחיוב בחודש',
            chargeImmediately: 'לחייב תרומה ראשונה החודש (מיידי)',
            otherAmount: 'סכום אחר',
            personalDetails: 'פרטים אישיים',
            firstName: 'שם פרטי',
            lastName: 'שם משפחה',
            phone: 'טלפון נייד',
            email: 'אימייל',
            address: 'כתובת',
            creditDetails: 'פרטי אשראי',
            cardNumber: 'מספר כרטיס',
            expiry: 'תוקף',
            securePayment: 'התשלום מאובטח בתקן PCI DSS המחמיר ביותר',
            submitBtn: 'תרום {amount} עכשיו',
            langBtn: 'English',
            errorGeneric: 'אירעה שגיאה. אנא נסה שנית.',
            errorMissing: 'אנא מלא את כל השדות החובה.',
            success: 'התרומה התקבלה בהצלחה! תודה רבה.'
        },
        en: {
            formTitle: 'Donation Form Example',
            formSubtitle: 'Your donation helps us continue our work. Every donation, small or large, joins the big change.',
            donationDetails: 'Donation Details',
            oneTime: 'One-time Donation',
            monthly: 'Monthly Subscription',
            chargeDay: 'Charge Day of Month',
            chargeImmediately: 'Charge first donation immediately',
            otherAmount: 'Other Amount',
            personalDetails: 'Personal Details',
            firstName: 'First Name',
            lastName: 'Last Name',
            phone: 'Mobile Phone',
            email: 'Email',
            address: 'Address',
            creditDetails: 'Credit Card Details',
            cardNumber: 'Card Number',
            expiry: 'Expiry',
            securePayment: 'Payment is secured by the strictest PCI DSS standard',
            submitBtn: 'Donate {amount} Now',
            langBtn: 'עברית',
            errorGeneric: 'An error occurred. Please try again.',
            errorMissing: 'Please fill in all required fields.',
            success: 'Donation received successfully! Thank you.'
        }
    };

    // Elements
    const wrapper = document.getElementById('sola-donation-wrapper');
    const form = document.getElementById('sola-donation-form');
    const amountBtns = document.querySelectorAll('.sola-amount-btn');
    const currBtns = document.querySelectorAll('.sola-curr-btn');
    const customAmountInput = document.getElementById('sola-custom-amount-input');
    const amountInput = document.getElementById('sola-amount');
    const currencyInput = document.getElementById('sola-currency');
    const freqOptions = document.querySelectorAll('.sola-freq-option');
    const monthlyOptions = document.getElementById('sola-monthly-options');
    const submitText = document.getElementById('sola-submit-text');
    const langToggle = document.getElementById('sola-lang-toggle');
    const messageDiv = document.getElementById('sola-message');
    const currSymbols = document.querySelectorAll('.sola-curr-symbol');

    // Helpers
    const getSymbol = (curr) => {
        switch (curr) {
            case 'ILS': return '₪';
            case 'USD': return '$';
            case 'EUR': return '€';
            default: return '₪';
        }
    };

    const updateUI = () => {
        // Update Amount Buttons
        amountBtns.forEach(btn => {
            if (parseInt(btn.dataset.amount) === state.amount && !customAmountInput.value) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update Currency Buttons
        currBtns.forEach(btn => {
            if (btn.dataset.curr === state.currency) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update Frequency
        freqOptions.forEach(opt => {
            const input = opt.querySelector('input');
            if (input.value === state.frequency) {
                opt.classList.add('active');
                input.checked = true;
            } else {
                opt.classList.remove('active');
            }
        });

        // Toggle Monthly Options
        if (state.frequency === 'monthly') {
            monthlyOptions.style.display = 'flex';
        } else {
            monthlyOptions.style.display = 'none';
        }

        // Update Submit Button Text
        const symbol = getSymbol(state.currency);
        const t = translations[state.lang];
        submitText.textContent = t.submitBtn.replace('{amount}', symbol + state.amount);

        // Update Currency Symbols
        currSymbols.forEach(el => el.textContent = symbol);
    };

    const updateLang = () => {
        const t = translations[state.lang];

        // Direction
        wrapper.setAttribute('dir', state.lang === 'he' ? 'rtl' : 'ltr');

        // Text Content
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            if (t[key]) el.textContent = t[key];
        });

        // Placeholders
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.dataset.i18nPlaceholder;
            if (t[key]) el.placeholder = t[key];
        });

        langToggle.textContent = t.langBtn;
        updateUI();
    };

    // Event Listeners

    // Amount Buttons
    amountBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            state.amount = parseInt(btn.dataset.amount);
            amountInput.value = state.amount;
            customAmountInput.value = '';
            updateUI();
        });
    });

    // Custom Amount
    customAmountInput.addEventListener('input', (e) => {
        const val = e.target.value;
        if (val) {
            state.amount = parseFloat(val);
            amountInput.value = state.amount;
            amountBtns.forEach(b => b.classList.remove('active'));
        } else {
            state.amount = 0;
        }
        updateUI();
    });

    // Currency Buttons
    currBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            state.currency = btn.dataset.curr;
            currencyInput.value = state.currency;
            updateUI();
        });
    });

    // Frequency
    freqOptions.forEach(opt => {
        opt.addEventListener('click', (e) => {
            if (e.target.tagName === 'INPUT') return;

            const input = opt.querySelector('input');
            state.frequency = input.value;
            updateUI();
        });
        opt.querySelector('input').addEventListener('change', (e) => {
            state.frequency = e.target.value;
            updateUI();
        });
    });

    // Language Toggle
    langToggle.addEventListener('click', () => {
        state.lang = state.lang === 'he' ? 'en' : 'he';
        updateLang();
    });

    // Form Submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        messageDiv.textContent = '';
        messageDiv.className = 'sola-message';

        // Basic Validation
        if (!state.amount || state.amount <= 0) {
            messageDiv.textContent = translations[state.lang].errorMissing;
            messageDiv.classList.add('error');
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Add state values explicitly
        data.amount = state.amount;
        data.currency = state.currency;
        data.frequency = state.frequency;

        // Checkbox handling
        if (data.frequency === 'monthly') {
            data.chargeImmediately = form.querySelector('#sola-charge-immediately').checked;
            data.chargeDay = form.querySelector('#sola-charge-day').value;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Processing...';

        try {
            const response = await fetch(solaData.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': solaData.nonce
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                messageDiv.textContent = translations[state.lang].success;
                messageDiv.classList.add('success');
                if (solaData.redirectUrl) {
                    window.location.href = solaData.redirectUrl;
                }
            } else {
                messageDiv.textContent = result.message || translations[state.lang].errorGeneric;
                messageDiv.classList.add('error');
            }
        } catch (error) {
            messageDiv.textContent = translations[state.lang].errorGeneric;
            messageDiv.classList.add('error');
            console.error(error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // Init
    updateUI();
});
