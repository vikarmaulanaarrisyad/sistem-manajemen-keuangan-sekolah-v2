<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ public_path('/AdminLTE/dist/css/adminlte.min.css') }}">
</head>

<body>

    <div class="row">
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-credit-card"></i> Tanda Bukti Setor Tunai
                        <small class="float-right">
                            Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:s') }}
                        </small>

                    </h4>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    Telah terima dari:
                    <address>
                        <strong>{{ $siswa->nama_lengkap ?? '' }}</strong><br>
                        Tempat, Tanggal Lahir : {{ $siswa->tempat_lahir }}, {{ $siswa->tgl_lahir }}<br>
                        Jenis Kelamin : {{ $siswa->jenis_kelamin->nama }}<br>
                        NISN/NIS : {{ $siswa->nisn }}/{{ $siswa->nis }}<br>
                        Rombel : {{ $siswa->siswa_rombel()->first()->kelas->nama }}
                        {{ $siswa->siswa_rombel()->first()->nama }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    Wali Kelas
                    <address>
                        <strong>{{ $guru->nama_lengkap }}</strong><br>
                        Email: {{ $guru->user->email }}
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <br>
                    <b>Nomor Berkas : {{ $tabungan->invoice }}</b><br>
                    <b>Tanggal Catat: {{ $tabungan->tanggal_transaksi }}</b><br>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $tabungan->tanggal_transaksi }}</td>
                                <td>{{ $tabungan->keterangan }}</td>
                                <td>{{ format_uang($tabungan->jumlah) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="mt-3">
                <strong>Terbilang:</strong>
                <em>{{ terbilang($tabungan->jumlah) }} Rupiah</em>
            </div>

            <!-- row for signatures -->
            <div class="row mt-5">
                <div class="col-6 text-center">
                    <p>Penyetor,</p>
                    <br><br><br>
                    <strong>{{ $siswa->nama_lengkap ?? '-' }}</strong>
                </div>
                <div class="col-6 text-center">
                    <p>Petugas,</p>
                    <br><br><br>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
            </div>
        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->

</body>

</html>
