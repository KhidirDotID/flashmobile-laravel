<?php

return [
    'client_id' => env('FLASH_CLIENT_ID'),
    'secret_key' => env('FLASH_SECRET_KEY'),
    'is_production' => env('FLASH_ENVIRONMENT', 'sandbox') === 'production'
];
