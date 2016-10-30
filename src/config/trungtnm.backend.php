<?php
return [
    /*
    | start of backend zone
    */
    'uri'                          => 'backend',
    'default_page_route'           => 'indexDashboard',
    'titleSuffix'                  => '',
    'uploadFolder'                 => 'uploads',
    'theme'                        => '',
    'modules_route_path'           => app_path('Http/Route/Backend'),
    'modules_controller_namespace' => 'App\Http\Controllers\Backend',
    'allowed_roles'                => [
        'root',
        'admin'
    ],
    'admin_roles'                  => [
        'root',
        'admin'
    ]
];