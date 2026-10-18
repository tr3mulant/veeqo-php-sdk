<?php

return [
    'api_key' => env('VEEQO_API_KEY'),

    /*
     * A cache store name (e.g. "redis") to share Veeqo's rate limit across workers.
     * Unset or empty disables proactive limiting.
     */
    'rate_limit_store' => env('VEEQO_RATE_LIMIT_STORE'),
];
