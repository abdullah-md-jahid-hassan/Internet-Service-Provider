<?php

if (!defined('PROJECT_PATH')) {
    define('PROJECT_PATH', 'http://localhost/pro_in_laragon/Internet-Service-Provider'); // replace this value with your project path
}

if (!defined('IS_SANDBOX')) {
    define('IS_SANDBOX', true); // 'true' for sandbox, 'false' for live
}

if (!defined('STORE_ID')) {
    define('STORE_ID', 'amjhl67e02337e567a'); // your store id. For sandbox, register at https://developer.sslcommerz.com/registration/
}

if (!defined('STORE_PASSWORD')) {
    define('STORE_PASSWORD', 'amjhl67e02337e567a@ssl'); // your store password.
}

return [
    'success_url' => 'payment/pg_redirection/success.php', // your success url
    'failed_url' => 'payment/pg_redirection/fail.php', // your fail url
    'cancel_url' => 'payment/pg_redirection/cancel.php', //your cancel url
    'ipn_url' => 'payment/pg_redirection/ipn.php', // your ipn url


    'projectPath' => PROJECT_PATH,
    'apiDomain' => IS_SANDBOX ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com',
    'apiCredentials' => [
        'store_id' => STORE_ID,
        'store_password' => STORE_PASSWORD,
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
    ],
    'connect_from_localhost' => false,
    'verify_hash' => true,
];
