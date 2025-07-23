<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tabungan Siswa</title>

    <link rel="stylesheet" href="{{ public_path('/AdminLTE/dist/css/adminlte.min.css') }}">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        .ttd {
            width: 200px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <h4 class="text-center">Laporan Tabungan Siswa</h4>
    <p class="text-center">
        Tanggal {{ tanggal_indonesia($start) }}
        s/d
        Tanggal {{ tanggal_indonesia($end) }}
    </p>

    <br>

    {{-- Informasi Siswa --}}
    <table style="margin-bottom: 2px;">
        <tr>
            <td><strong>Nama Siswa</strong></td>
            <td>: {{ $siswa->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            @php
                $rombel = $siswa->siswa_rombel->first(); // ambil rombel pertama
            @endphp

            <td>:
                {{ $rombel?->kelas?->nama ?? '-' }}
                {{ $rombel?->nama ?? '-' }}
            </td>

        </tr>
    </table>

    {{-- Tabel Transaksi --}}
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Pemasukan</th>
                <th class="text-center">Pengeluaran</th>
                <th class="text-center">Sisa Kas</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $key => $col)
                        <td
                            @if ($key === 'DT_RowIndex' || $key === 'tanggal') class="text-center"
                            @elseif (in_array($key, ['pemasukan', 'pengeluaran', 'sisa'])) class="text-right"
                            @elseif ($key === 'keterangan') class="text-left" @endif>
                            {!! $col !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd">
        <p>Wali Kelas</p>
        <br><br><br>
        <p><strong>{{ $siswa->kelas->wali_kelas->nama ?? '________________' }}</strong></p>
    </div>

</body>

</html>
