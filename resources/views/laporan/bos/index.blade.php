@extends('layouts.app')

@section('title', 'Laporan Dana BOS')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Laporan Dana BOS</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <form id="filter-form" class="form-inline flex-wrap gap-2">
                        <div class="form-group mr-3 mb-2">
                            <label for="tahun_pelajaran_id" class="mr-2 font-weight-bold">Tahun Pelajaran</label>
                            <select name="tahun_pelajaran_id" id="tahun_pelajaran_id" class="form-control" required>
                                <option value="">Pilih Tahun Pelajaran</option>
                                @foreach ($tahun_pelajaran as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-3 mb-2">
                            <label for="start" class="mr-2 font-weight-bold">Dari</label>
                            <input type="date" name="start" id="start" class="form-control" required>
                        </div>

                        <div class="form-group mr-3 mb-2">
                            <label for="end" class="mr-2 font-weight-bold">Sampai</label>
                            <input type="date" name="end" id="end" class="form-control" required>
                        </div>

                        <div class="form-group mb-2">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>

                        <div class="form-group mb-2 ml-2">
                            <a id="btn-export-pdf" target="_blank" href="#" class="btn btn-danger d-none">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        </div>
                    </form>
                </x-slot>

                <div class="table-responsive">
                    <x-table class="table-bordered table-striped" id="table">
                        <x-slot name="thead">
                            <th style="width:5%">No</th>
                            <th>Tanggal</th>
                            <th class="text-left">Pemasukan</th>
                            <th class="text-left">Pengeluaran</th>
                            <th class="text-left">Sisa Saldo</th>
                            <th>Keterangan</th>
                        </x-slot>
                    </x-table>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@includeIf('includes.datatables')

@push('scripts')
    <script>
        let table;

        function loadTable(params = {}) {
            if (table) table.destroy();
            table = $('#table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('laporan.bos.data') }}',
                    data: params
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'pemasukan',
                        className: 'text-right'
                    },
                    {
                        data: 'pengeluaran',
                        className: 'text-right'
                    },
                    {
                        data: 'sisa',
                        className: 'text-right'
                    },
                    {
                        data: 'keterangan',
                        className: 'text-left'
                    },
                ],
                paginate: false,
                searching: false,
                bInfo: false,
                order: []
            });
        }

        $('#filter-form').on('submit', function(e) {
            e.preventDefault();

            const tahun_pelajaran_id = $('#tahun_pelajaran_id').val();
            const start = $('#start').val();
            const end = $('#end').val();

            if (!tahun_pelajaran_id || !start || !end) {
                alert('Semua filter wajib diisi.');
                return;
            }

            loadTable({
                tahun_pelajaran_id: tahun_pelajaran_id,
                start: start,
                end: end
            });

            const exportUrl =
                `{{ route('laporan.bos.export_pdf', ['start' => '__start__', 'end' => '__end__']) }}?tahun_pelajaran_id=${tahun_pelajaran_id}`;
            $('#btn-export-pdf')
                .removeClass('d-none')
                .attr('href', exportUrl.replace('__start__', start).replace('__end__', end));
        });
    </script>
@endpush
