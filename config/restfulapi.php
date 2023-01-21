<?php

return [
    /*
     |
     | register routes to access
     |
     */
    'routes' => [
        'users' => [
            'model' => \App\Models\User::class,
            'rules' => [
                'store' => [
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ]
        ],
        'bookings' => [
            'model' => \App\Models\Booking::class,
            'rules' => [
                'store' => [
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ]
        ],
    ],

    /*
     |
     | Add prefix to routes restful routes controlled by the Slait Restful package. For example, if the route prefix is
     | is 'admin', then the routes in this package will be /api/admin/{endpoint}, /api/admin/{endpoint}/{id} etc. the
     | endpoint variable is defined in the routes array above. e.g /api/admin/users/1, /api/admin/users. You can find more
     | about the routes by running the command 'php artisan route:list'
     |
     */
    'prefix' => 'general',

    /*
     |
     | Register your middlewares in the array to guard the routes of the package.
     |
     */
    'middlewares' => [
        'admin',
        'auth:sanctum',
    ]

];
