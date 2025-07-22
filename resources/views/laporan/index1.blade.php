@extends('layouts.app')

@section('title', 'Laporan Tabungan Siswa')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <form method="GET" action="{{ route('laporan.tabungan.index') }}" class="form-inline">
                        <label for="siswa_id" class="mr-2">Pilih Siswa:</label>
                        <select name="siswa_id" class="form-control mr-2" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ $siswa_id == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>

                        <input type="date" name="start" value="{{ $start }}" class="form-control mr-2" required>
                        <input type="date" name="end" value="{{ $end }}" class="form-control mr-2" required>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th width="25%" class="text-left">Pemasukan</th>
                        <th width="25%" class="text-left">Pengeluaran</th>
                        <th width="25%" class="text-left">Sisa Saldo</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@includeIf('includes.datatables')

@push('scripts')
    <script>
        let table;

        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporan.tabungan.data') }}',
                data: {
                    start: '{{ $start }}',
                    end: '{{ $end }}',
                    siswa_id: '{{ $siswa_id }}'
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'pemasukan',
                    searchable: false,
                    sortable: false,
                    className: 'text-right'
                },
                {
                    data: 'pengeluaran',
                    searchable: false,
                    sortable: false,
                    className: 'text-right'
                },
                {
                    data: 'sisa',
                    searchable: false,
                    sortable: false,
                    className: 'text-right'
                },
            ],
            paginate: false,
            searching: false,
            bInfo: false,
            order: []
        });
    </script>
@endpush
