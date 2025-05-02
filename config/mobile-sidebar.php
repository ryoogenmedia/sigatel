<?php

return [
    [
        'title' => 'Beranda',
        'icon' => 'home-alt',
        'route-name' => 'mobile.home',
        'is-active' => 'mobile.home',
        'description' => 'Untuk melihat ringkasan aplikasi anda.',
        'roles' => ['teacher','parent'],
    ],
    [
        'title' => 'Setting',
        'icon' => 'settings',
        'route-name' => 'mobile.setting.account.index',
        'is-active' => 'mobile.setting*',
        'description' => 'Untuk melihat pengaturan anda.',
        'roles' => ['teacher','parent'],
        'sub-menus' => [
            [
                'title' => 'Akun',
                'description' => 'Melihat pengaturan akun.',
                'route-name' => 'mobile.setting.account.index',
                'is-active' => 'mobile.setting.account.index',
            ],
            [
                'title' => 'Profil',
                'description' => 'Melihat pengaturan profil.',
                'route-name' => 'mobile.setting.profile.index',
                'is-active' => 'mobile.setting.profile.index',
            ],
        ]
    ],
];
