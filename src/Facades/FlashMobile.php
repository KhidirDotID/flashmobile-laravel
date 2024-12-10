<?php

namespace KhidirDotID\FlashMobile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setClientId(string $clientId)
 * @method static void setSecretKey(string $secretKey)
 * @method static string|null generateAuth()
 * @method static array createQRPayment(array $data)
 * @method static array getPaymentStatus(string $transactionId)
 */
class FlashMobile extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'flashmobile';
    }
}
