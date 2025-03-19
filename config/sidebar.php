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
        'title' => 'Laporan',
        'description' => 'Untuk cetak laporan data admin.',
        'icon' => 'print',
        'route-name' => 'report.student',
        'is-active' => 'report*',
        'roles' => ['admin'],
        'sub-menus' => [
            [
                'title' => 'Siswa',
                'description' => 'Untuk cetak laporan siswa.',
                'route-name' => 'report.student',
                'is-active' => 'report.student',
            ],
            [
                'title' => 'Orang Tua Siswa',
                'description' => 'Untuk cetak laporan orang tua siswa.',
                'route-name' => 'report.parent-student',
                'is-active' => 'report.parent-student',
            ],
            [
                'title' => 'Guru',
                'description' => 'Untuk cetak laporan guru.',
                'route-name' => 'report.teacher',
                'is-active' => 'report.teacher',
            ],
            [
                'title' => 'Kelas',
                'description' => 'Untuk cetak laporan kelas.',
                'route-name' => 'report.grand',
                'is-active' => 'report.grand',
            ],
            [
                'title' => 'Mata Pelajaran',
                'description' => 'Untuk cetak laporan mata pelajaran.',
                'route-name' => 'report.school-subject',
                'is-active' => 'report.school-subject',
            ],
            [
                'title' => 'Piket',
                'description' => 'Untuk cetak laporan piket siswa.',
                'route-name' => 'report.on-duty',
                'is-active' => 'report.on-duty',
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
