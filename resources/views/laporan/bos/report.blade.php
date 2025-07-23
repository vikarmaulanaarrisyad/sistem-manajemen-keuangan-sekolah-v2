<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Dana BOS</title>

    <link rel="stylesheet" href="{{ public_path('/AdminLTE/dist/css/adminlte.min.css') }}">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h4,
        h5 {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .ttd {
            width: 200px;
            float: right;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <h4 class="text-center">Laporan Keuangan Dana BOS</h4>
    <h5 class="text-center">Periode: {{ tanggal_indonesia($start) }} s/d {{ tanggal_indonesia($end) }}</h5>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Pemasukan</th>
                <th style="text-align: center;">Pengeluaran</th>
                <th style="text-align: center;">Keterangan</th>
                <th style="text-align: center;">Sisa Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                @if ($row['DT_RowIndex'] !== '')
                    <tr>
                        <td style="text-align: center;">{{ $row['DT_RowIndex'] }}</td>
                        <td style="text-align: center;">{{ $row['tanggal'] }}</td>
                        <td style="text-align: right;">
                            @if ((float) $row['pemasukan'] > 0)
                                {{ $row['pemasukan'] }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if ((float) $row['pengeluaran'] > 0)
                                {{ $row['pengeluaran'] }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="text-align: left;">
                            {{ $row['keterangan'] ?? '-' }}
                        </td>
                        <td style="text-align: right;">
                            {{ $row['sisa'] }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Saldo Akhir</strong></td>
                        <td style="text-align: right;"><strong>{{ $row['sisa'] }}</strong></td>
                    </tr>
                @endif
            @endforeach
        </tbody>

    </table>


    {{-- Tanda Tangan --}}
    <br><br><br>
    <table style="width: 100%; border: none; margin-top: 50px;">
        <tr>
            <td style="text-align: center; border: none;">
                Mengetahui,<br>
                <strong>Kepala Sekolah</strong><br><br><br><br><br>
                <strong>{{ $kepala_sekolah->nama ?? '________________' }}</strong>
            </td>
            <td style="text-align: center; border: none;">
                , {{ tanggal_indonesia(now()) }}<br>
                <strong>Bendahara BOS</strong><br><br><br><br><br>
                <strong>{{ $bendahara->nama ?? '________________' }}</strong>
            </td>
        </tr>
    </table>


</body>

</html>
