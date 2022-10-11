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
        'force_logout' => false,
    ],
    /* =========================
    *  Path segmentation
    *  e.g., if the segment is enabled then allowed_path = ['admin', 'employee']
    *  www.domain.com/admin/ <== Locked, because this segment path is not added to the allowed_path
    *  www.domain.com/employee/ <== Locked, because this segment path is not added to the allowed_path
    *  www.domain.com/portal/ <== unlocked and will not be check by the locker middleware even if the user lock their screen
    *
    */
    'segment' => [
        'specific_path_only' => false, // if false, then all the request will be blocked by the locker and will be redirected to the authentication page
        'segment_needle' => 1, // see the https://laravel.com/api/9.x/Illuminate/Http/Request.html#method_segment
        'allowed_path' => [] //
    ]
];
