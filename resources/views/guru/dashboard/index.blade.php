@extends('layouts.app')

@section('content')
    @can('read-tabungan-siswa')
        <div class="row">
            <!-- Info Box Setor Tunai -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Setor Tunai</span>
                        <span class="info-box-number">Rp {{ number_format($totalSetor ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Box Tarik Tunai -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-arrow-up"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tarik Tunai</span>
                        <span class="info-box-number">Rp {{ number_format($totalTarik ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Box Saldo Total -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Saldo</span>
                        <span class="info-box-number">Rp
                            {{ number_format(($totalSetor ?? 0) - ($totalTarik ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Box Jumlah Siswa -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-primary elevation-1">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jumlah Siswa yang Diajar</span>
                        <span class="info-box-number">{{ $jumlahSiswaDiajar }} Siswa</span>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('read-keuangan-sekolah')
        <div class="row">
            <div class="col-lg-4 col-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ format_uang($totalPemasukan) }}</h3>
                        <p>Pemasukan Dana Bos Tahun Pelajaran {{ $tahunPelajaran->nama }} {{ $tahunPelajaran->semester->nama }}
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-4">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp {{ format_uang($totalPengeluaran) }}</h3>
                        <p>Pengeluaran Dana Bos Tahun Pelajaran {{ $tahunPelajaran->nama }}
                            {{ $tahunPelajaran->semester->nama }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>

            {{-- Tambahan Sisa Saldo --}}
            <div class="col-lg-4 col-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>Rp {{ format_uang($saldoBOS) }}</h3>
                        <p>Sisa Saldo Dana BOS Tahun Pelajaran {{ $tahunPelajaran->nama }}
                            {{ $tahunPelajaran->semester->nama }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('read-tabungan-siswa')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Tabungan Per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tabunganPerBulanChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Grafik Tabungan Per Tahun</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tabunganPerTahunChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <x-card>
                    <x-slot name="header">
                        <h3 class="card-title">Daftar Saldo per Siswa</h3>
                    </x-slot>
                    <table id="saldoPerSiswaTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Pemasukan</th>
                                <th>Pengeluaran</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--  @dd($saldoPerSiswa)  --}}
                            @foreach ($saldoPerSiswa as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nama_lengkap }}</td>
                                    <td>Rp {{ number_format($siswa->total_setor ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($siswa->total_tarik ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($siswa->saldo ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-card>
            </div>
        </div>
    @endcan
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#saldoPerSiswaTable').DataTable();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var tabunganPerBulan = @json($tabunganPerBulan);
        var bulanLabels = tabunganPerBulan.map(item => 'Bulan ' + item.bulan);
        var totalSetorPerBulan = tabunganPerBulan.map(item => item.total_setor);
        var totalTarikPerBulan = tabunganPerBulan.map(item => item.total_tarik);

        new Chart(document.getElementById('tabunganPerBulanChart'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                        label: 'Setor Tunai',
                        data: totalSetorPerBulan,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Tarik Tunai',
                        data: totalTarikPerBulan,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            }
        });

        var tabunganPerTahun = @json($tabunganPerTahun);
        var tahunLabels = tabunganPerTahun.map(item => item.tahun);
        var totalSetorPerTahun = tabunganPerTahun.map(item => item.total_setor);
        var totalTarikPerTahun = tabunganPerTahun.map(item => item.total_tarik);

        new Chart(document.getElementById('tabunganPerTahunChart'), {
            type: 'line',
            data: {
                labels: tahunLabels,
                datasets: [{
                        label: 'Setor Tunai',
                        data: totalSetorPerTahun,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Tarik Tunai',
                        data: totalTarikPerTahun,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.1,
                        fill: false
                    }
                ]
            }
        });
    </script>
@endpush
