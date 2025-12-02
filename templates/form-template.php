<div id="sola-donation-wrapper" class="sola-wrapper" dir="rtl">
    <div class="sola-container">
        
        <!-- Header Section -->
        <div class="sola-header">
            <div class="sola-header-bg"></div>
            <div class="sola-header-content">
                <div class="sola-icon-circle">
                    <!-- Heart Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400 fill-rose-400"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>
                <h1 class="sola-title" data-i18n="formTitle">טופס תרומה לדוגמא</h1>
                <p class="sola-subtitle" data-i18n="formSubtitle">
                    התרומה שלך עוזרת לנו להמשיך בעשייה. כל תרומה, קטנה כגדולה, מצטרפת לשינוי הגדול.
                </p>
                <button id="sola-lang-toggle" type="button">English</button>
            </div>
        </div>

        <form id="sola-donation-form" class="sola-form-body">
            
            <!-- Section 1: Donation Details -->
            <section class="sola-section">
                <div class="sola-section-header">
                    <div class="sola-section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <h2 data-i18n="donationDetails">פרטי התרומה</h2>
                </div>

                <!-- Frequency Toggle -->
                <div class="sola-frequency-toggle">
                    <label class="sola-freq-option active">
                        <input type="radio" name="frequency" value="onetime" checked>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <span data-i18n="oneTime">תרומה חד פעמית</span>
                    </label>
                    <label class="sola-freq-option">
                        <input type="radio" name="frequency" value="monthly">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span data-i18n="monthly">הוראת קבע חודשית</span>
                    </label>
                </div>

                <!-- Monthly Options (Hidden by default) -->
                <div id="sola-monthly-options" class="sola-monthly-options" style="display: none;">
                    <div class="sola-input-group">
                        <label data-i18n="chargeDay">יום לחיוב בחודש</label>
                        <select name="chargeDay" id="sola-charge-day">
                            <?php for($i=1; $i<=28; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="sola-checkbox-group">
                        <input type="checkbox" name="chargeImmediately" id="sola-charge-immediately" checked>
                        <label for="sola-charge-immediately" data-i18n="chargeImmediately">לחייב תרומה ראשונה החודש (מיידי)</label>
                    </div>
                </div>

                <!-- Currency & Amount Selection -->
                <div class="sola-amount-section">
                    <div class="sola-currency-selector">
                        <button type="button" class="sola-curr-btn active" data-curr="ILS">ILS</button>
                        <button type="button" class="sola-curr-btn" data-curr="USD">USD</button>
                        <button type="button" class="sola-curr-btn" data-curr="EUR">EUR</button>
                    </div>
                    <input type="hidden" name="currency" id="sola-currency" value="ILS">

                    <div class="sola-amount-grid">
                        <button type="button" class="sola-amount-btn" data-amount="50">50</button>
                        <button type="button" class="sola-amount-btn" data-amount="100">100</button>
                        <button type="button" class="sola-amount-btn active" data-amount="180">180</button>
                        <button type="button" class="sola-amount-btn" data-amount="360">360</button>
                        <button type="button" class="sola-amount-btn" data-amount="500">500</button>
                    </div>
                    <input type="hidden" name="amount" id="sola-amount" value="180">

                    <div class="sola-custom-amount">
                        <span class="sola-curr-symbol">₪</span>
                        <input type="number" id="sola-custom-amount-input" placeholder="סכום אחר" data-i18n-placeholder="otherAmount">
                    </div>
                </div>
            </section>

            <div class="sola-divider"></div>

            <!-- Section 2: Personal Details -->
            <section class="sola-section">
                <div class="sola-section-header">
                    <div class="sola-section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h2 data-i18n="personalDetails">פרטים אישיים</h2>
                </div>
                
                <div class="sola-grid-2">
                    <div class="sola-input-group">
                        <label data-i18n="firstName">שם פרטי</label>
                        <input type="text" name="firstName" required>
                    </div>
                    <div class="sola-input-group">
                        <label data-i18n="lastName">שם משפחה</label>
                        <input type="text" name="lastName" required>
                    </div>
                    
                    <div class="sola-input-group">
                        <label data-i18n="phone">טלפון נייד</label>
                        <div class="sola-input-wrapper">
                            <svg class="sola-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input type="tel" name="phone" required>
                        </div>
                    </div>

                    <div class="sola-input-group">
                        <label data-i18n="email">אימייל</label>
                        <div class="sola-input-wrapper">
                            <svg class="sola-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <input type="email" name="email" required>
                        </div>
                    </div>

                    <div class="sola-input-group full-width">
                        <label data-i18n="address">כתובת</label>
                        <div class="sola-input-wrapper">
                            <svg class="sola-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <input type="text" name="address" required>
                        </div>
                    </div>
                </div>
            </section>

            <div class="sola-divider"></div>

            <!-- Section 3: Payment Details -->
            <section class="sola-section">
                 <div class="sola-section-header">
                   <div class="sola-section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                  </div>
                  <h2 data-i18n="creditDetails">פרטי אשראי</h2>
                </div>
                
                <div class="sola-credit-card-box">
                    <div class="sola-grid-2">
                        <div class="sola-input-group full-width">
                            <label data-i18n="cardNumber">מספר כרטיס</label>
                            <div class="sola-input-wrapper">
                                <svg class="sola-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                <input type="text" name="ccNumber" placeholder="0000 0000 0000 0000" required>
                            </div>
                        </div>

                        <div class="sola-input-group">
                           <label data-i18n="expiry">תוקף</label>
                           <input type="text" name="ccExpiry" placeholder="MM / YY" class="text-center" required>
                        </div>

                        <div class="sola-input-group">
                           <label>CVV</label>
                           <input type="text" name="ccCvv" placeholder="123" class="text-center" required>
                        </div>
                    </div>
                    
                    <div class="sola-secure-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span data-i18n="securePayment">התשלום מאובטח בתקן PCI DSS המחמיר ביותר</span>
                    </div>
                </div>
            </section>

            <!-- Submit Button -->
            <button type="submit" class="sola-submit-btn">
                 <span id="sola-submit-text">תרום ₪180 עכשיו</span>
                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="fill-white/20"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </button>
            <div id="sola-message" class="sola-message"></div>

        </form>
    </div>
</div>
