<?php

// config for filament-lockscreen/FilamentLockscreen
return [
    /* =======================================
     *   if `enable_redirect_to` is TRUE then after login, it will be redirected to the route setup under `redirect_route`
     */
    'enable_redirect_to' => false,
    'redirect_route' => 'filament.pages.dashboard',

    /* =======================================
    *   RATE LIMIT
     *  change to false the `enable_rate_limit` to disable preventing user to input after several login failure.
    */
    'rate_limit' => [
        'enable_rate_limit' => true,
        'rate_limit_max_count' => 5, // max count for failure login allowed.
    ],
];
