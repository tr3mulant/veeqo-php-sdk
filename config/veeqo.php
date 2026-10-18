<?php

return [
    'api_key' => env('VEEQO_API_KEY'),

    /*
     * Your Appstore app's OAuth client, used by VeeqoOAuth. It is only bound when all three are set.
     */
    'client_id' => env('VEEQO_CLIENT_ID'),
    'client_secret' => env('VEEQO_CLIENT_SECRET'),
    'redirect_uri' => env('VEEQO_REDIRECT_URI'),

    /*
     * A cache store name (e.g. "redis") to share Veeqo's rate limit across workers.
     * Unset or empty disables proactive limiting.
     */
    'rate_limit_store' => env('VEEQO_RATE_LIMIT_STORE'),
];
