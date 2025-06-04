@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Setor</h5>
                    <h3>Rp{{ number_format($totalSetorSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Tarik</h5>
                    <h3>Rp{{ number_format($totalTarikSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Saldo Akhir</h5>
                    <h3>Rp{{ number_format($saldoSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <x-card>
                <ul class="nav nav-tabs mb-3" id="tab-bulan" role="tablist">
                    @foreach (range(1, 12) as $bulan)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $bulan == now()->format('n') ? 'active' : '' }}" data-bs-toggle="tab"
                                type="button" data-bulan="{{ $bulan }}">
                                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="table-responsive">
                    <table id="tabel-tabungan" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pemasukan</th>
                                <th>Pengeluaran</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </x-card>
        </div>
    </div>


    <div class="row my-4">
        <div class="col-lg-12">
            <x-card>
                <h5>Grafik Saldo Bulanan</h5>
                <canvas id="grafikSaldo" height="100"></canvas>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let currentMonth = new Date().getMonth() + 1;

        function loadDataTable(bulan) {
            $('#tabel-tabungan').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                responsive: true,
                ajax: {
                    url: '{{ route('dashboard.siswa.tabungan') }}',
                    data: {
                        bulan: bulan
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'pemasukan',
                        name: 'pemasukan'
                    },
                    {
                        data: 'pengeluaran',
                        name: 'pengeluaran'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo'
                    },
                ]
            });
        }

        $(document).ready(function() {
            loadDataTable(currentMonth);

            $('#tab-bulan .nav-link').on('click', function() {
                $('#tab-bulan .nav-link').removeClass('active');
                $(this).addClass('active');
                let bulan = $(this).data('bulan');
                loadDataTable(bulan);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $.get("{{ route('dashboard.siswa.grafik-saldo') }}", function(response) {
                const labels = response.map(item => item.bulan);
                const saldo = response.map(item => item.saldo);

                const ctx = document.getElementById('grafikSaldo').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Saldo Bulanan',
                            data: saldo,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
