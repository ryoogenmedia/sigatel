<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DATA LAPORAN PENUGASAN KELAS</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            font-size: 14px;
            margin: 40px auto 0;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid black;
        }

        thead {
            background-color: rgb(226, 226, 226);
        }

        h4,
        h3 {
            text-align: center;
            margin: 10px 0;
        }

        .logo-container {
            padding: 5px 0;
            text-align: center;
        }

        .logo {
            width: 300px;
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="logo-container">
        <img class="logo" src="{{ public_path('ryoogenmedia/logo-dark.png') }}" alt="logo">
    </div>

    <h4>DATA LAPORAN PENUGASAN KELAS</h4>

    @if ($date_start || $date_end)
        <h3>
            {{ implode(
                ' - ',
                array_filter([
                    $date_start ? \Carbon\Carbon::parse($date_start)->translatedFormat('M Y') : null,
                    $date_end ? \Carbon\Carbon::parse($date_end)->translatedFormat('M Y') : null,
                ]),
            ) }}
        </h3>
    @endif

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama Kelas</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Deskripsi Tugas</th>
                <th>Status Pemberian</th>
                <th>Waktu Pemberian - Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $grade_assignment)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $grade_assignment->grade->name ?? '-' }}</td>
                    <td>{{ $grade_assignment->teacher->name ?? '-' }}</td>
                    <td>{{ $grade_assignment->school_subject->name ?? '-' }}</td>
                    <td>{!! $grade_assignment->description !!}</td>
                    <td>{{ $grade_assignment->status ? 'Sudah Diberikan' : 'Belum Diberikan' }}</td>
                    <td>
                        <span class="me-2">{{ $grade_assignment->schedule }}</span>
                        <span>{{ $grade_assignment->finish }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px;">
                        <p style="color: #929292; margin-top: 10px; padding-bottom: 0px"><b>Data Belum Tersedia</b></p>
                        <p style="color: #c4c4c4; margin-top: 0px;">Sesuaikan filter anda untuk mencari data yang
                            tersedia.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
