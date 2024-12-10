# flashmobile-laravel
A Flash Mobile Wrapper for Laravel

## Installation

1. Install the package
    ```bash
    composer require khidirdotid/flashmobile-laravel
    ```
2. Publish the config file
    ```bash
    php artisan vendor:publish --provider="KhidirDotID\FlashMobile\Providers\FlashMobileServiceProvider"
    ```
3. Add the Facade to your `config/app.php` into `aliases` section
    ```php
    'FlashMobile' => KhidirDotID\FlashMobile\Facades\FlashMobile::class,
    ```
4. Add ENV data
    ```env
    FLASH_CLIENT_ID=
    FLASH_SECRET_KEY=
    FLASH_ENVIRONMENT=sandbox
    ```

    or you can set it through the controller
    ```php
    \FlashMobile::setClientId('FLASH_CLIENT_ID');
    \FlashMobile::setSecretKey('FLASH_SECRET_KEY');
    \FlashMobile::setProduction(false);
    ```

## Usage

### Create QR Payment

1. Get Payment QR String
    ```php
    $data = [
        'terminal_id' => 'INV-' . time(),
        'external_id' => 'INV-' . time(),
        'amount' => 10000,
        'session_time' => 1, // in minutes
        'fullname' => '',
        'email' => '',
        'phone_number' => ''
    ];

    try {
        // Get QR String
        $payment = \FlashMobile::createQRPayment($data);

        // Combine with QR Generator Package. e.g: simplesoftwareio/simple-qrcode
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::generate($payment['qr_status']);
        echo '<img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" class="qrcode">';
    } catch (\Throwable $th) {
        throw $th;
    }
    ```

### Handle HTTP Notification

1. Create route to handle notifications
    ```php
    Route::match(['GET', 'POST'], 'flash.ipn', [PaymentController::class, 'flashIpn'])->name('flash.ipn');
    ```
2. Create method in controller
    ```php
    public function paymentIpn(Request $request)
    {
        try {
            $response = \FlashMobile::getPaymentStatus($request->transaction_id);

            if (strtolower($response['status'] == 'success') {
                // TODO: Set payment status in merchant's database to 'success'
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    ```
3. Except verify CSRF token in `app/Http/Middleware/VerifyCsrfToken.php`
    ```php
    protected $except = [
        'flash/ipn'
    ];
    ```
