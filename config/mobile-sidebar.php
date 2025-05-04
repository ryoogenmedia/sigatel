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
        'title' => 'Daftar Siswa Kelas',
        'icon' => 'weight',
        'route-name' => 'mobile.grade.index',
        'is-active' => 'mobile.grade*',
        'description' => 'Untuk melihat dadftar siswa kelas.',
        'roles' => ['teacher'],
    ],
    [
        'title' => 'Bikin Tugas Kelas',
        'icon' => 'book',
        'route-name' => 'mobile.grand-assignment.index',
        'is-active' => 'mobile.grand-assignment*',
        'description' => 'Untuk membuat tugas kelas.',
        'roles' => ['teacher'],
    ],
    [
        'title' => 'Tugas Piket Anda',
        'icon' => 'calendar',
        'route-name' => 'mobile.duty.index',
        'is-active' => 'mobile.duty*',
        'description' => 'Untuk melihat piket anda.',
        'roles' => ['teacher'],
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
