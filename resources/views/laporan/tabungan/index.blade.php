@extends('layouts.app')

@section('title', 'Laporan Tabungan Siswa')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Laporan Tabungan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <form id="filter-form" class="form-inline flex-wrap gap-2">
                        <div class="form-group mr-3 mb-2">
                            <label for="kelas_id" class="mr-2 font-weight-bold">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-3 mb-2">
                            <label for="rombel_id" class="mr-2 font-weight-bold">Rombel</label>
                            <select name="rombel_id" id="rombel_id" class="form-control" required>
                                <option value="">Pilih Rombel</option>
                            </select>
                        </div>

                        <div class="form-group mr-3 mb-2">
                            <label for="siswa_id" class="mr-2 font-weight-bold">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="">Pilih Siswa</option>
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
                            <a id="btn-export-pdf" target="_blank" href="#" class="btn btn-danger "><i
                                    class="fas fa-file-pdf"></i> Export PDF</a>

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
                    url: '{{ route('laporan.tabungan.data') }}',
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

        // Kelas -> Rombel
        $('#kelas_id').on('change', function() {
            const kelasId = $(this).val();
            $('#rombel_id').html('<option value="">Memuat...</option>');
            $('#siswa_id').html('<option value="">Pilih Siswa</option>');

            if (kelasId) {
                $.get('{{ route('get.rombels.by.kelas') }}', {
                    kelas_id: kelasId
                }, function(data) {
                    let options = '<option value="">Pilih Rombel</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.nama}</option>`;
                    });
                    $('#rombel_id').html(options);
                });
            }
        });

        // Rombel -> Siswa
        $('#rombel_id').on('change', function() {
            const rombelId = $(this).val();
            $('#siswa_id').html('<option value="">Memuat...</option>');

            if (rombelId) {
                $.get('{{ route('get.siswas.by.rombel') }}', {
                    rombel_id: rombelId
                }, function(data) {
                    let options = '<option value="">Pilih Siswa</option>';
                    data.forEach(item => {
                        options +=
                            `<option value="${item.id}">${item.nama_lengkap ?? item.nama}</option>`;
                    });
                    $('#siswa_id').html(options);
                });
            }
        });

        // Filter Form Submit
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();

            const siswa_id = $('#siswa_id').val();
            const start = $('#start').val();
            const end = $('#end').val();

            if (!siswa_id || !start || !end) {
                alert('Semua filter wajib diisi.');
                return;
            }

            loadTable({
                siswa_id: siswa_id,
                start: start,
                end: end
            });

            const exportUrl =
                `{{ route('laporan.tabungan.export_pdf', ['start' => '__start__', 'end' => '__end__']) }}?siswa_id=${siswa_id}`;
            $('#btn-export-pdf')
                .removeClass('d-none')
                .attr('href', exportUrl.replace('__start__', start).replace('__end__', end));
        });
    </script>
@endpush
