<?php

namespace KhidirDotID\FlashMobile;

use Illuminate\Support\Facades\Http;

class FlashMobile
{
    public function __construct()
    {
        self::registerFlashMobileConfig();
    }

    // Flash Mobile Base Url
    protected static $baseUrl;

    // Flash Mobile Client Id
    protected static $clientId;

    // Flash Mobile Secret Key
    protected static $secretKey;

    // Flash Mobile Auth Token
    protected static $token;

    public static function registerFlashMobileConfig()
    {
        // Set your Flash Mobile Client Id
        self::setClientId(config('flashmobile.client_id'));
        // Set your Flash Mobile Secret Key
        self::setSecretKey(config('flashmobile.secret_key'));

        $isProduction = config('flashmobile.is_production');
        self::$baseUrl = $isProduction ? 'https://app.flashmobile.co.id' : 'https://sandbox-app.flashmobile.co.id';
    }

    /**
     * Set your Flash Mobile Client Id
     *
     * @static
     */
    public static function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    /**
     * Set your Flash Mobile Secret Key
     *
     * @static
     */
    public static function setSecretKey($secretKey)
    {
        self::$secretKey = $secretKey;
    }

    public static function generateAuth(): string|null
    {
        $auth = Http::post(self::$baseUrl . '/priv/v1/pg/token', [
            'client_key' => self::$clientId,
            'server_key' => self::$secretKey
        ]);

        if ($auth->successful()) {
            $data = $auth->json('data');
            self::$token = $data['token'];

            return $data['token'];
        }

        return null;
    }

    /**
     * Create QR Payment
     *
     * Example:
     *
     * ```php
     *   $data = [
     *       'terminal_id' => 'INV-' . time(),
     *       'external_id' => 'INV-' . time(),
     *       'amount' => 10000,
     *       'session_time' => 1, // in minutes
     *       'fullname' => '',
     *       'email' => '',
     *       'phone_number' => ''
     *   ];
     *   $payment = \FlashMobile::createQRPayment($data);
     * ```
     *
     * @param  array $data Payment options
     */
    public static function createQRPayment(array $data): array
    {
        $token = self::generateAuth();

        $createQrPayment = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post(self::$baseUrl . '/payment/api/v1/qris/payment', $data);

        return $createQrPayment->json('data');
    }

    /**
     * Get payment status by transaction id
     *
     * @param  string $transactionId Transaction ID
     */
    public static function getPaymentStatus($transactionId): array
    {
        $paymentStatus = Http::get(self::$baseUrl . '/payment/api/v1/qris/payment-status/' . $transactionId);

        return $paymentStatus->json('data');
    }
}
