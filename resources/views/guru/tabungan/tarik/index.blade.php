@extends('layouts.app')

@section('title', 'Tarik Tunai')
@section('subtitle', 'Tarik Tunai')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card"></i>
                        @yield('subtitle')
                    </h3>
                </x-slot>
                <div class="row">
                    <div class="col-md-4">
                        <x-card>
                            <x-slot name="header">Tambah Tarik Tunai</x-slot>
                            <form id="formTarik">
                                <input type="hidden" name="jenis_transaksi" value="tarik">
                                @csrf
                                <div class="form-group">
                                    <label>Pilih Peserta Didik</label>
                                    <select class="form-control select2" name="peserta_didik_id" id="peserta_didik_id">
                                        <option value="">-- Pilih Peserta Didik --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Setoran</label>
                                    <div class="input-group datepicker" id="tanggal" data-target-input="nearest">
                                        <input type="text" name="tanggal" class="form-control datetimepicker-input"
                                            data-target="#tanggal" data-toggle="datetimepicker" autocomplete="off" />
                                        <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" onkeyup="format_uang(this)"
                                        autocapitalize="off">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" autocapitalize="off">-</textarea>
                                </div>
                                <button type="submit" class="btn btn-success" id="btnSimpan" disabled>SIMPAN</button>

                            </form>
                        </x-card>
                    </div>

                    <div class="col-md-8">
                        <x-card>
                            <x-slot name="header">Rekap Data Tarik Tunai</x-slot>
                            <table class="table table-bordered" id="tableTarik">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pesdik</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </x-card>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datepicker')
@include('includes.datatables')
@include('includes.select2')

@push('scripts')
    <script>
        let table;
        // Cek awal saat halaman dimuat
        $(document).ready(function() {
            if ($('#peserta_didik_id').val() === '') {
                $('#btnSimpan').prop('disabled', true);
            }
        });

        table = $('#tableTarik').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tarik.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal_transaksi',
                },
                {
                    data: 'nama_lengkap',
                    name: 'nama_lengkap'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                    className: 'text-right', // Mengatur agar data berada di posisi kanan
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Select2
        $('#peserta_didik_id').select2({
            placeholder: "-- Pilih Peserta Didik --",
            allowClear: true,
            ajax: {
                url: "{{ route('tarik.getSiswa') }}", // Ganti dengan route yang sesuai
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // kata kunci pencarian
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Disable tombol simpan jika peserta didik belum dipilih
        $('#peserta_didik_id').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue === '') {
                $('#btnSimpan').prop('disabled', true);
            } else {
                $('#btnSimpan').prop('disabled', false);
            }
        });

        // fungsi submit
        $('#formTarik').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            Swal.fire({
                title: 'Yakin ingin simpan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('tarik.store') }}",
                        method: "POST",
                        data: formData,
                        success: function(response, textStatus, xhr) {
                            if (xhr.status === 201 || xhr.status === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    table.ajax.reload();
                                    $('#btnSimpan').prop('disabled', true);
                                    // Clear the form inputs
                                    $('#formTarik')[0].reset();
                                    $('#peserta_didik_id')
                                        .val('')
                                        .trigger('change');
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close(); // Tutup Swal Loading
                            let errorMessage = "Terjadi kesalahan!";
                            if (xhr.responseJSON?.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: errorMessage,
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            if (xhr.status === 422) {
                                loopErrors(xhr.responseJSON.errors);
                            }
                        }
                    });
                }
            });
        });

        function downloadPDF(url) {
            window.open(url, '_blank');
        }

        function deleteData(url, siswa, tanggal, jumlah) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah anda yakin?',
                text: 'Menghapus transaksi untuk siswa ' + siswa + ' tanggal ' + tanggal + ' dengan jumlah: ' +
                    jumlah,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya Hapus',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: function(response, textStatus, xhr) {
                            if (xhr.status === 201 || xhr.status === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    table.ajax.reload();
                                    $('#btnSimpan').prop('disabled', true);
                                    // Clear the form inputs
                                    $('#formTarik')[0].reset();
                                    $('#peserta_didik_id')
                                        .val('')
                                        .trigger('change');
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // Menampilkan pesan error
                            Swal.fire({
                                icon: 'error',
                                title: 'Opps! Gagal',
                                text: xhr.responseJSON.message,
                                showConfirmButton: true,
                            }).then(() => {
                                table.ajax.reload();
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
