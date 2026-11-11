<?php

$prod = (bool) env('MIDTRANS_IS_PRODUCTION', false);

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => $prod,
    'snap_api' => $prod ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com',
    'core_api' => $prod ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com',
    'snap_js' => ($prod ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com') . '/snap/snap.js',
];
