@extends('layouts.app')

@section('title', 'Detail Rombongan Belajar')
@section('subtitle', 'Detail Rombongan Belajar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rombel.index') }}">Rombongan Belajar</a></li>
    <li class="breadcrumb-item active">Detail Rombongan Belajar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h6 class="card-title"><i class="fas fa-users mr-1 mt-2"></i>@yield('subtitle')</h6>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div>
                                <a href="{{ route('rombel.edit', $rombel->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus-circle"></i> Edit Rombel
                                </a>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Tahun Pelajaran</span>
                                <span class="info-box-number text-center text-muted mb-0">
                                    {{ $rombel->tahun_pelajaran->nama }}
                                    {{ $rombel->tahun_pelajaran->semester->nama }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Wali Kelas</span>
                                <span class="info-box-number text-center text-muted mb-0">
                                    {{ $rombel->walikelas->nama_lengkap ?? 'Belum ada' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Tingkat Kelas</span>
                                <span class="info-box-number text-center text-muted mb-0">
                                    {{ $rombel->kelas->tingkat }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">Nama Rombel</span>
                                <span class="info-box-number text-center text-muted mb-0">
                                    {{ $rombel->nama }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge badge-info" style="font-size: 15px !important;">Jumlah Siswa: <strong
                            id="jumlahSiswa">{{ $rombel->siswa_rombel->count() ?? 0 }}</strong></span>
                </div>

                <x-table class="siswa">
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>NIS</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;

        table = $('.siswa').DataTable({
            serverSide: true,
            autoWidth: false,
            responsive: true,
            paging: false, // Nonaktifkan pagination agar semua data ditampilkan
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('rombel.getSiswaRombel', $rombel->id) }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'nis'
                },
            ],
            dom: 'Brt',
            bSort: false,
        });
    </script>
@endpush
