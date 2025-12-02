<div id="sola-donation-wrapper" class="sola-wrapper" dir="rtl">
    
    <!-- Floating Background Elements -->
    <div class="sola-floating-bg" style="width: 400px; height: 400px; top: 10%; left: 10%;"></div>
    <div class="sola-floating-bg" style="width: 300px; height: 300px; bottom: 10%; right: 10%; animation-delay: -10s;"></div>

    <div class="sola-container">
        
        <!-- Header Section -->
        <div class="sola-glass-card">
            <div class="sola-flex-between">
                <div>
                    <h1 class="sola-title" data-i18n="formTitle">תרומה בטוחה</h1>
                    <p class="sola-subtitle" data-i18n="formSubtitle">כל תרומה עושה את ההבדל</p>
                </div>
                <button id="sola-lang-toggle" class="sola-lang-toggle" type="button">English</button>
            </div>
        </div>

        <form id="sola-donation-form">
            
            <!-- Section 1: Personal Details -->
            <div class="sola-glass-card">
                <h2 class="sola-section-title">
                    <div class="sola-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span data-i18n="personalDetails">פרטים אישיים</span>
                </h2>
                
                <div class="sola-grid-2">
                    <div class="sola-input-group">
                        <input type="text" name="firstName" class="sola-glass-input" placeholder="שם פרטי" data-i18n-placeholder="firstName" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="sola-input-group">
                        <input type="text" name="lastName" class="sola-glass-input" placeholder="שם משפחה" data-i18n-placeholder="lastName" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    
                    <div class="sola-input-group">
                        <input type="tel" name="phone" class="sola-glass-input sola-number-font" placeholder="טלפון" data-i18n-placeholder="phone" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>

                    <div class="sola-input-group">
                        <input type="email" name="email" class="sola-glass-input" placeholder="דואר אלקטרוני" data-i18n-placeholder="email" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </div>
                </div>
                
                <div class="sola-input-group">
                    <input type="text" name="address" class="sola-glass-input" placeholder="כתובת מגורים" data-i18n-placeholder="address" required>
                    <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
            </div>

            <!-- Section 2: Donation Details -->
            <div class="sola-glass-card">
                <h2 class="sola-section-title">
                    <div class="sola-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <span data-i18n="donationDetails">פרטי תרומה</span>
                </h2>

                <div class="sola-grid-2 sola-mb-6">
                    <!-- Currency -->
                    <div>
                        <label class="sola-label" data-i18n="currency">מטבע</label>
                        <div class="sola-flex-gap">
                            <button type="button" class="sola-amount-btn sola-curr-btn active" data-curr="ILS">₪</button>
                            <button type="button" class="sola-amount-btn sola-curr-btn" data-curr="USD">$</button>
                            <button type="button" class="sola-amount-btn sola-curr-btn" data-curr="EUR">€</button>
                        </div>
                        <input type="hidden" name="currency" id="sola-currency" value="ILS">
                    </div>

                    <!-- Frequency -->
                    <div>
                        <label class="sola-label" data-i18n="donationDetails">פרטי תרומה</label> <!-- Reusing label as per design -->
                        <div class="sola-flex-gap">
                            <label class="sola-amount-btn sola-freq-option active">
                                <input type="radio" name="frequency" value="monthly" checked style="display:none;">
                                <span data-i18n="monthly">חודשי</span>
                            </label>
                            <label class="sola-amount-btn sola-freq-option">
                                <input type="radio" name="frequency" value="onetime" style="display:none;">
                                <span data-i18n="oneTime">חד פעמי</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Amount Selection -->
                <div class="sola-mb-6">
                    <label class="sola-label" data-i18n="amount">סכום התרומה</label>
                    <div class="sola-grid-4 sola-mb-4">
                        <button type="button" class="sola-amount-btn" data-amount="50"><span class="sola-curr-symbol">₪</span>50</button>
                        <button type="button" class="sola-amount-btn" data-amount="100"><span class="sola-curr-symbol">₪</span>100</button>
                        <button type="button" class="sola-amount-btn active" data-amount="180"><span class="sola-curr-symbol">₪</span>180</button>
                        <button type="button" class="sola-amount-btn" data-amount="500"><span class="sola-curr-symbol">₪</span>500</button>
                    </div>
                    <input type="hidden" name="amount" id="sola-amount" value="180">

                    <div class="sola-input-group">
                        <input type="number" id="sola-custom-amount-input" class="sola-glass-input sola-number-font" placeholder="סכום אחר" data-i18n-placeholder="customAmount">
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="1" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>

                <!-- Monthly Options -->
                <div id="sola-monthly-options" style="display: block;">
                    <div class="sola-mb-6">
                        <label class="sola-label" data-i18n="chargeDay">יום לחיוב</label>
                        <div class="sola-input-group">
                            <select name="chargeDay" id="sola-charge-day" class="sola-glass-input sola-number-font">
                                <?php for($i=1; $i<=28; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        </div>
                    </div>

                    <label class="sola-checkbox-wrapper">
                        <input type="checkbox" name="chargeImmediately" id="sola-charge-immediately" class="sola-checkbox-custom" checked>
                        <span data-i18n="chargeNowLabel">לחייב את התרומה הראשונה מהחודש הנוכחי</span>
                    </label>
                </div>
            </div>

            <!-- Section 3: Payment Details -->
            <div class="sola-glass-card">
                <div class="sola-flex-between sola-mb-6">
                    <h2 class="sola-section-title" style="margin-bottom: 0;">
                        <div class="sola-icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </div>
                        <span data-i18n="paymentDetails">פרטי תשלום</span>
                    </h2>
                    <div class="sola-security-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span data-i18n="secure">תשלום מאובטח</span>
                    </div>
                </div>
                
                <div class="sola-input-group">
                    <input type="text" name="ccNumber" class="sola-glass-input sola-number-font" placeholder="מספר כרטיס אשראי" data-i18n-placeholder="cardNumber" maxlength="19" required>
                    <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>

                <div class="sola-grid-2">
                    <div class="sola-input-group">
                        <input type="text" name="ccExpiry" class="sola-glass-input sola-number-font" placeholder="תוקף (MM/YY)" data-i18n-placeholder="expiry" maxlength="5" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                    <div class="sola-input-group">
                        <input type="text" name="ccCvv" class="sola-glass-input sola-number-font" placeholder="CVV" data-i18n-placeholder="cvv" maxlength="4" required>
                        <svg class="sola-input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                </div>

                <div class="sola-flex-gap" style="justify-content: center; opacity: 0.6; font-size: 0.875rem; margin-top: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span data-i18n="encrypted">מוצפן 256-bit</span>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="sola-donate-btn">
                 <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                 <span id="sola-submit-text">תרום ₪180 עכשיו</span>
            </button>
            <div id="sola-message" class="sola-message"></div>

        </form>
    </div>
</div>
