<?php
// kline service configurations.

return [

    'api_url' => env('KLINE_API_URL', 'http://localhost:8989'),

    'api_token' => env('KLINE_API_TOKEN', 'XXX-XXX-XXX-XXX'),

    'api_endpoint' => env('KLINE_API_ENDPOINT', '/symbol'),
];
