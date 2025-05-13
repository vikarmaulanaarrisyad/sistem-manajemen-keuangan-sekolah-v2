@extends('layouts.app')

@section('title', 'Dashboard')

@section('subtitle', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $guru }}</h3>

                    <p>GTK</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <a href="{{ route('guru.index') }}" class="small-box-footer">Selengkapnya <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $siswa }}</h3>

                    <p>Siswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('siswa.index') }}" class="small-box-footer">Selengkapnya <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $rombel }}</h3>

                    <p>Ruang / Rombel</p>
                </div>
                <div class="icon">
                    <i class="fab fa-instalod"></i>
                </div>
                <a href="{{ route('rombel.index') }}" class="small-box-footer">Selengkapnya <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $kurikulum }}</h3>

                    <p>Kurikulum</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <a href="{{ route('kurikulum.index') }}" class="small-box-footer">Selengkapnya <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    {{--  <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Jumlah Siswa</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartSiswa"></canvas>
                </div>
            </div>
        </div>
    </div>  --}}
@endsection
{{--  @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('chartSiswa').getContext('2d');

            var chartSiswa = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Siswa'], // Satu label umum
                    datasets: [{
                            label: 'Laki-laki',
                            data: [{{ $siswaLaki }}], // Data untuk laki-laki
                            backgroundColor: 'rgba(54, 162, 235, 0.6)', // Biru
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Perempuan',
                            data: [{{ $siswaPerempuan }}], // Data untuk perempuan
                            backgroundColor: 'rgba(255, 99, 132, 0.6)', // Merah
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 100,
                            }
                        }]
                    }
                }
            });

        });
    </script>
@endpush  --}}
