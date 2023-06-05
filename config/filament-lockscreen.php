<?php
return [

    /*
     *  Lock Screen Icon
     */
    'icon' => 'heroicon-s-lock-closed',

    /*
     *  Lock Screen URL
     * 
     * Note: do not provide base url `/` or empty, otherwise it will return default url
     */
    'url' => '/screen/lock',

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
    *  Path segmentation locking
    *  e.g., if the segment is enabled then locked_path = ['admin', 'employee']
    *  www.domain.com/admin/ <== Locked, because this segment path is added to the locked_path
    *  www.domain.com/employee/ <== Locked, because this segment path is added to the locked_path
    *  www.domain.com/portal/ <== unlocked and will not be checked by the locker middleware even if the user lock their screen
    *
    * Note: make sure your segment_needle and allowed path is aligned
    */
    'segment' => [
        'specific_path_only' => false, // if false, then all the request will be blocked by the locker and will be redirected to the authentication page
        'segment_needle' => 1, // see the https://laravel.com/api/9.x/Illuminate/Http/Request.html#method_segment
        'locked_path' => [] //
    ]
];
