@extends('layouts.app')

@section('content')
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

    <!-- Grafik Tabungan Per Bulan dan Per Tahun -->
    <div class="row">
        <!-- Card Grafik Tabungan Per Bulan -->
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

        <!-- Card Grafik Tabungan Per Tahun -->
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
                            <th>Nama Siswa</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saldoPerSiswa as $siswa)
                            <tr>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td>Rp {{ number_format($siswa->saldo ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#saldoPerSiswaTable').DataTable();
        });
    </script>
    <!-- Script untuk Grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data Tabungan Per Bulan
        var tabunganPerBulan = @json($tabunganPerBulan);
        var bulanLabels = tabunganPerBulan.map(function(item) {
            return 'Bulan ' + item.bulan;
        });
        var totalSetorPerBulan = tabunganPerBulan.map(function(item) {
            return item.total_setor;
        });
        var totalTarikPerBulan = tabunganPerBulan.map(function(item) {
            return item.total_tarik;
        });

        var ctx1 = document.getElementById('tabunganPerBulanChart').getContext('2d');
        new Chart(ctx1, {
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

        // Data Tabungan Per Tahun
        var tabunganPerTahun = @json($tabunganPerTahun);
        var tahunLabels = tabunganPerTahun.map(function(item) {
            return item.tahun;
        });
        var totalSetorPerTahun = tabunganPerTahun.map(function(item) {
            return item.total_setor;
        });
        var totalTarikPerTahun = tabunganPerTahun.map(function(item) {
            return item.total_tarik;
        });

        var ctx2 = document.getElementById('tabunganPerTahunChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: tahunLabels,
                datasets: [{
                        label: 'Setor Tunai',
                        data: totalSetorPerTahun,
                        fill: false,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.1
                    },
                    {
                        label: 'Tarik Tunai',
                        data: totalTarikPerTahun,
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.1
                    }
                ]
            }
        });
    </script>
@endpush
