<?php

return [
    // 'defaults' => [
    //     'guard' => 'student',
    //     'passwords' => 'students',
    // ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'student' => [
            'driver' => 'jwt',
            'provider' => 'students'
        ],
        'teacher' => [
            'driver' => 'jwt',
            'provider' => 'teachers',
        ],
        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admin',
        ]
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class
        ],
        'teachers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Teacher::class
        ],
        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ]
    ]
];
