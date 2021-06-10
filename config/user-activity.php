<?php

return [
    'activated'        => true, // active/inactive all logging
    'middleware'       => ['web', 'auth'],
    'route_path'       => 'http://bsk2.herrmanneasyedit.com/core/admin/user-activity',
    'admin_panel_path' => 'http://bsk2.herrmanneasyedit.com/core/admin',
    'delete_limit'     => 7, // default 7 days

    'model' => [
        'user' => "App\User"
    ],

    'log_events' => [
        'on_create'     => true,
        'on_edit'       => true,
        'on_delete'     => true,
        'on_login'      => true,
        'on_logout'      => true,
        'on_lockout'    => true
    ]
];
