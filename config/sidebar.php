<?php

return [
    [
        'title' => 'Beranda',
        'icon' => 'home',
        'route-name' => 'home',
        'is-active' => 'home',
        'description' => 'Untuk melihat ringkasan aplikasi.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Pengguna',
        'icon' => 'user',
        'route-name' => 'user.index',
        'is-active' => 'user*',
        'description' => 'Untuk kelola akun pengguna.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Guru',
        'icon' => 'chalkboard-teacher',
        'route-name' => 'teacher.index',
        'is-active' => 'teacher*',
        'description' => 'Untuk kelola data guru.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Siswa',
        'icon' => 'graduation-cap',
        'route-name' => 'student.index',
        'is-active' => 'student*',
        'description' => 'Untuk kelola data guru.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Orang Tua Siswa',
        'icon' => 'users',
        'route-name' => 'guardian-parent.index',
        'is-active' => 'guardian-parent*',
        'description' => 'Untuk kelola data guru.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Kelas',
        'icon' => 'school',
        'route-name' => 'grade.index',
        'is-active' => 'grade*',
        'description' => 'Untuk kelola data kelas.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Mata Pelajaran',
        'icon' => 'newspaper',
        'route-name' => 'school-subject.index',
        'is-active' => 'school-subject*',
        'description' => 'Untuk kelola data mata pelajaran.',
        'roles' => ['admin'],
    ],

    [
        'title' => 'Pengaturan',
        'description' => 'Menampilkan pengaturan aplikasi.',
        'icon' => 'cog',
        'route-name' => 'setting.profile.index',
        'is-active' => 'setting*',
        'roles' => ['admin'],
        'sub-menus' => [
            [
                'title' => 'Profil Sekolah',
                'description' => 'Melihat pengaturan profil sekolah.',
                'route-name' => 'setting.profile.index',
                'is-active' => 'setting.profile*',
            ],
            [
                'title' => 'Akun',
                'description' => 'Melihat pengaturan akun.',
                'route-name' => 'setting.account.index',
                'is-active' => 'setting.account*',
            ],
        ],
    ],
];
