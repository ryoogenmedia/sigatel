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
        'title' => 'Piket Siswa',
        'description' => 'Untuk kelola data piket siswa.',
        'icon' => 'calendar-plus',
        'route-name' => 'on-duty.assignment.index',
        'is-active' => 'on-duty*',
        'roles' => ['admin'],
        'sub-menus' => [
            [
                'title' => 'Penugasan',
                'description' => 'Melihat data penugasan piket siswa.',
                'route-name' => 'on-duty.assignment.index',
                'is-active' => 'on-duty.assignment*',
            ],
            [
                'title' => 'Masukan / Evaluasi',
                'description' => 'Kelola data masukan ke siswa.',
                'route-name' => 'on-duty.feedback.index',
                'is-active' => 'on-duty.feedback*',
            ],
        ],
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
