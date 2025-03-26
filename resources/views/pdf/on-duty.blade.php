<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DATA LAPORAN PIKET</title>

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
        <img class="logo" src="{{ logo_url() }}" alt="logo">
    </div>

    <h4>DATA LAPORAN PIKET</h4>

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
                <th>SISWA</th>
                <th>STATUS</th>
                <th>DESKRIPSI</th>
                <th>JENIS PELANGGARAN</th>
                <th>JADWAL PENUGASAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $on_duty)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td>{{ $on_duty->student->name ?? '-' }}</td>
                    <td>{{ $on_duty->status ?? '-' }}</td>
                    <td>{{ $on_duty->description ?? '-' }}</td>
                    <td>{{ $on_duty->violation_type ?? '-' }}</td>
                    <td>
                        <p class="mb-1">{{ Carbon\Carbon::parse($on_duty->schedule_time)->format('d/m/Y H:i:s') }}</p>
                        <p>{{ Carbon\Carbon::parse($on_duty->finish_time)->format('d/m/Y H:i:s') }}</p>
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
