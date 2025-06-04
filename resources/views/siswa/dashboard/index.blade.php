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
    <div class="row">
        <div class="col-lg-12">
            <x-card>
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
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabel-tabungan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('dashboard.siswa.tabungan') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
        });
    </script>
@endpush
