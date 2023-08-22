<?php

return [


    /*
     *  Lock Screen Icon
     */
    'icon' => 'heroicon-s-lock-closed',

    /*
     * Lock Screen URL
     *
     * Note: do not provide base url `/` or empty, otherwise it will return default url
     */
    'url' => '/screen/lock',

    /*
    | ------------------------------------------------------------------------------------------------
    |   Table Column Name
    | ------------------------------------------------------------------------------------------------
    | Change the table column name if your login authentication column is checking on a different field and not on the default field ('email and password') column of the table.
    */
    'table_columns' => [
        'account_username_field' => 'email',
        'account_password_field' => 'password',
    ],

    /* =======================================
    *   RATE LIMIT
     *  change to false the `enable_rate_limit` to disable preventing user to input after several login failure.
    */
    'rate_limit' => [
        'enable_rate_limit' => true,
        'rate_limit_max_count' => 5, // max count for failure login allowed.
        'force_logout' => false,
    ],
];
