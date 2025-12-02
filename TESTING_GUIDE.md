# Testing Guide: Sola Donations for Elementor

This guide outlines the steps to verify the functionality of the Sola Donations plugin in a WordPress environment.

## 1. Prerequisites
-   **WordPress Site** with Elementor installed and activated.
-   **Sola Payments Sandbox Account**:
    -   Go to [Sola Payments / Cardknox Sandbox](https://www.cardknox.com/sandbox/) to request a sandbox account if you don't have one.
    -   You will need your **Transaction Key (xKey)** and **iFields Key**.

## 2. Plugin Configuration
1.  **Activate Plugin**: Go to `Plugins > Installed Plugins` and activate "Sola Donations for Elementor".
2.  **Configure Settings**:
    -   Navigate to `Admin Dashboard > Sola Donations`.
    -   Enter your **xKey** (e.g., `sola-sandbox-key-123`).
    -   Enter your **iFields Key** (e.g., `ifields-sandbox-key-456`).
    -   Set **Environment** to `Sandbox`.
    -   Click **Save Settings**.

## 3. Widget Setup
1.  Create or Edit a Page with Elementor.
2.  Search for the **Sola Donation Form** widget.
3.  Drag it onto the page.
4.  **Configure Controls**:
    -   **Content Tab**:
        -   Set a Title (e.g., "Support Us").
        -   Set Predefined Amounts (e.g., `10, 25, 50`).
        -   Enable "Allow Custom Amount".
        -   **Payment Options**: Select "Both (User Selects)" for Donation Type.
        -   **Wallets**: Enable "Google/Apple Pay".
        -   **Merchant IDs**: Enter dummy IDs for testing (e.g., `merchant.com.example` for Apple, `1234567890` for Google). *Note: In a real environment, these must be valid IDs registered with Sola/Cardknox.*
    -   **Style Tab**:
        -   Customize Button Color.
        -   **Input Fields (Sola)**: Change font size or border color to verify iFields styling works.
5.  **Publish** the page.

## 4. Test Scenarios

### Scenario A: One-Time Donation
1.  Open the page on the frontend.
2.  Select an amount (e.g., $10).
3.  Enter **Test Card Details**:
    -   **Number**: `4111111111111111` (Visa)
    -   **Exp**: `1225` (Any future date)
    -   **CVV**: `123`
4.  Click **Donate Now**.
5.  **Expected Result**:
    -   Button text changes to "Processing...".
    -   Success message appears: "Thank you for your donation!".
    -   (Optional) Check your Cardknox Sandbox portal to see a `cc:sale` transaction for $10.

### Scenario B: Recurring Donation (Charge Immediately)
1.  Refresh the page.
2.  Select an amount (e.g., $25).
3.  Check **"Make this a monthly donation"**.
4.  Enter Test Card Details.
5.  Click **Donate Now**.
6.  **Expected Result**:
    -   Success message appears.
    -   **Backend Verification**:
        -   One `cc:sale` transaction for $25 (Immediate charge).
        -   One `schedule:create` transaction starting **Next Month** for $25/month.

### Scenario C: Wallet Payment (Google/Apple Pay)
1.  Refresh the page.
2.  **Prerequisite**: You must be on **HTTPS**. Wallets will not load on HTTP.
3.  Click the **Google Pay** or **Apple Pay** button.
4.  **Expected Result**:
    -   The native payment sheet should open (if supported by your browser/device).
    -   After authorizing, the form should submit and show "Donation Successful".
    -   *Note: In Sandbox with dummy Merchant IDs, the sheet might open but fail to process. This confirms the button logic is working.*

### Scenario D: Failed Transaction
1.  Refresh the page.
2.  Enter a **Declined Test Card**:
    -   **Number**: `4222222222222222` (Simulates Decline)
    -   **Exp**: `1225`
    -   **CVV**: `123`
3.  Click **Donate Now**.
4.  **Expected Result**:
    -   Error message appears: "Transaction declined." (or similar).

## 5. Troubleshooting
-   **"Invalid API Key"**: Check your settings in the Admin Dashboard.
-   **iFields not loading**: Ensure your `iFields Key` is correct and you are on HTTPS (required for iFields).
-   **Wallets not appearing**:
    -   Ensure "Enable Google/Apple Pay" is checked in Elementor.
    -   Ensure you are on **HTTPS**.
    -   Check Console for "Sola SDK (ck) not ready" errors.
-   **Console Errors**: Open Browser Developer Tools (F12) > Console to see any JS errors.
