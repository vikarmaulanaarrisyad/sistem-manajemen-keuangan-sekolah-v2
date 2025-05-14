@extends('layouts.app')

@section('title', 'Pemasukan Keuangan Sekolah')
@section('subtitle', 'Pemasukan Keuangan Sekolah')

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
                            <x-slot name="header">Tambah Pemasukan Keuangan Sekolah</x-slot>
                            <form id="formPemasukan">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_sumber">Sumber Dana</label>
                                    <input id="nama_sumber" class="form-control" type="text" name="nama_sumber"
                                        autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Diterima</label>
                                    <div class="input-group datepicker" id="tanggal_terima" data-target-input="nearest">
                                        <input type="text" name="tanggal_terima"
                                            class="form-control datetimepicker-input" data-target="#tanggal_terima"
                                            data-toggle="datetimepicker" autocomplete="off" />
                                        <div class="input-group-append" data-target="#tanggal_terima"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="text" class="form-control" name="jumlah" onkeyup="format_uang(this)"
                                        autocapitalize="off">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" name="keterangan" autocapitalize="off">-</textarea>
                                </div>
                                <button type="submit" class="btn btn-success" id="btnSimpan">SIMPAN</button>
                            </form>
                        </x-card>
                    </div>

                    <div class="col-md-8">
                        <x-card>
                            <x-slot name="header">Rekap Data Pemasukan</x-slot>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablePemasukan">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Sumber Dana</th>
                                            <th>Jumlah</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

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
        table = $('#tablePemasukan').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            searching: false,
            ajax: "{{ route('pemasukan.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal_terima',
                    name: 'tanggal_terima'
                },
                {
                    data: 'nama_sumber',
                    name: 'nama_sumber'
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
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // fungsi submit
        $('#formPemasukan').on('submit', function(e) {
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
                        url: "{{ route('pemasukan.store') }}",
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
                                    // Clear the form inputs
                                    $('#formPemasukan')[0].reset();

                                    // Bersihkan class is-invalid tapi jangan hapus elemen feedback-nya
                                    $('#formPemasukan .is-invalid').removeClass(
                                        'is-invalid');

                                    // Opsional: kosongkan isi feedback jika perlu
                                    $('#formPemasukan .invalid-feedback').text('');
                                });
                            }
                        },
                        error: function(err) {
                            Swal.close(); // Tutup Swal Loading

                            let errorMessage = "Terjadi kesalahan!";

                            // Cek jika ada pesan error dari server
                            if (err.responseJSON && err.responseJSON.message) {
                                errorMessage = err.responseJSON.message;
                            }

                            // Tampilkan Swal error
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: errorMessage,
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            // Jika error 422 (validasi), tampilkan detailnya
                            if (err.status === 422 && err.responseJSON.errors) {
                                loopErrors(err.responseJSON.errors);
                            }
                        }

                    });
                }
            });
        });

        function deleteData(url, nama, tanggal, jumlah) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah anda yakin?',
                text: 'Menghapus data untuk ' + nama + ' tanggal ' + tanggal + ' dengan jumlah: ' +
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
                                    // Clear the form inputs
                                    $('#formPemasukan')[0].reset();
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

        function updateStatus(id) {
            let _token = $('meta[name="csrf-token"]').attr('content'); // Ambil CSRF token dari meta tag

            // Tampilkan Swal Loading
            Swal.fire({
                title: "Memproses...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.ajax({
                url: '/guru/pemasukan/update-status/' + id,
                type: 'PUT',
                data: {
                    _token: _token // CSRF Token untuk keamanan
                },
                success: function(response) {
                    Swal.close(); // Tutup loading

                    // Tampilkan notifikasi toastr sukses
                    toastr.success(response.message, "Berhasil!", {
                        timeOut: 2000
                    });

                    table.ajax.reload();
                    let icon = $('a[kodeq="' + id + '"]').find('i');

                    if (icon.length > 0) {
                        console.log("Status Baru:", response.new_status);
                        console.log("Class Sebelum:", icon.attr("class"));

                        if (response.new_status == 1) {
                            icon.removeClass('fa-toggle-off text-danger')
                                .addClass('fa-toggle-on text-success');
                        } else {
                            icon.removeClass('fa-toggle-on text-success')
                                .addClass('fa-toggle-off text-danger');
                        }
                    }

                    table.ajax.reload();
                },
                error: function(xhr) {
                    Swal.close(); // Tutup loading

                    // Tampilkan notifikasi toastr error
                    if (xhr.status === 400) {
                        // Tampilkan notifikasi toastr error jika tidak boleh menonaktifkan status terakhir
                        toastr.error(xhr.responseJSON.message, "Gagal!", {
                            timeOut: 2000
                        });
                    } else {
                        toastr.error("Terjadi kesalahan saat memperbarui status.", "Gagal!", {
                            timeOut: 2000
                        });
                    }
                }
            });
        }
    </script>
@endpush
