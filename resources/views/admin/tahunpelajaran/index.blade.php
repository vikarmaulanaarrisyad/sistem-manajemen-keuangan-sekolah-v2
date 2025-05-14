@extends('layouts.app')

@section('title', 'Tahun Pelajaran')

@section('subtitle', 'Tahun Pelajaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Tahun Pelajaran</li>
@endsection

@push('css')
    <style>
        .status-toggle {
            border: none !important;
            background: none !important;
            outline: none !important;
        }
    </style>
@endpush


@section('content')
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-calendar-alt fa-3x text-success"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-success">📅 Tahun Pelajaran</h5>
                            <p class="mb-2 text-dark">
                                Pastikan tahun pelajaran yang digunakan sudah sesuai dengan kalender akademik terbaru.
                            </p>
                            <p class="mb-0">
                                Atur tahun pelajaran dengan benar

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1 mt-2"></i>
                        Tahun Pelajaran
                    </h3>
                    <div class="card-tools">
                        <button onclick="addForm(`{{ route('tahunpelajaran.store') }}`)" class="btn btn-sm btn-primary"><i
                                class="fas fa-plus-circle"></i> Tambah Data</button>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Semester</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.tahunpelajaran.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('tahunpelajaran.data') }}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama'
                },
                {
                    data: 'semester.nama'
                },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function addForm(url, title = 'Form Tahun Pelajaran') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Tahun Pelajaran') {
            Swal.fire({
                title: "Memuat...",
                text: "Mohon tunggu sebentar...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan spinner loading
                }
            });

            $.get(url)
                .done(response => {
                    Swal.close(); // Tutup loading setelah sukses
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');

                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    Swal.close(); // Tutup loading jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON?.message || 'Terjadi kesalahan saat memuat data.',
                        showConfirmButton: true,
                    });

                    if (errors.status == 422) {
                        loopErrors(errors.responseJSON.errors);
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
                url: '/admin/tahunpelajaran/update-status/' + id,
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

                    window.location.reload();
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

        function submitForm(originalForm) {
            $(button).prop('disabled', true);

            // Menampilkan Swal loading
            Swal.fire({
                title: 'Mohon Tunggu...',
                text: 'Sedang memproses data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: $(originalForm).attr('action'),
                type: $(originalForm).attr('method') || 'POST', // Gunakan method dari form
                data: new FormData(originalForm),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response, textStatus, xhr) {
                    Swal.close(); // Tutup Swal Loading

                    if (xhr.status === 201 || xhr.status === 200) {
                        $(modal).modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            table.ajax.reload(); // Reload DataTables
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close(); // Tutup Swal Loading
                    $(button).prop('disabled', false);

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
    </script>
@endpush
