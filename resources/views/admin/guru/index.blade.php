@extends('layouts.app')

@section('title', 'Guru')

@section('subtitle', 'Guru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Guru</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1 mt-2"></i>
                        @yield('subtitle')
                    </h3>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div>
                                <button onclick="confirmExport()" type="button" class="btn btn-danger btn-sm"><i
                                        class="fas fa-download"></i>
                                    Export Data
                                </button>

                                <button onclick="confirmImport()" type="button" class="btn btn-success btn-sm"><i
                                        class="fas fa-file-excel"></i>
                                    Import Excel
                                </button>

                                <button onclick="addForm(`{{ route('guru.store') }}`)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>TMT Guru</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.guru.form')
    @include('admin.guru.import-excel')
@endsection

@include('includes.datatables')
@include('includes.datepicker')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let importExcel = '#importExcelModal';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('guru.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'tempat_lahir'
                },
                {
                    data: 'tgl_lahir'
                },
                {
                    data: 'jenis_kelamin.nama'
                },
                {
                    data: 'tmt_guru'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        $('#filterkurikulum').on('change', function() {
            table.ajax.reload();
        })

        function addForm(url, title = 'Form Guru') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Guru') {
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

        function confirmExport() {
            Swal.fire({
                title: 'Konfirmasi',
                html: `
                <p>Apakah Anda yakin ingin mengunduh file? Pastikan Anda telah memahami risiko yang ada.</p>
                <label>
                    <input type="checkbox" id="agreeCheckbox" onchange="toggleDownload()"> Saya setuju dengan risiko yang ada
                </label>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Download',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    confirmButton.disabled = true; // Disable tombol saat pertama kali muncul

                    document.getElementById('agreeCheckbox').addEventListener('change', function() {
                        confirmButton.disabled = !this
                            .checked; // Aktifkan tombol jika checkbox dicentang
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    exportEXCEL();
                }
            });
        }

        function exportEXCEL() {
            Swal.fire({
                title: 'Sedang Memproses...',
                text: 'Mohon tunggu sementara file sedang diproses.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = '{{ route('guru.exportEXCEL') }}';

            // Tutup loading setelah beberapa detik (opsional)
            setTimeout(() => {
                Swal.close();
            }, 3000);
        }

        function confirmImport() {
            $(importExcel).modal('show');
        }
    </script>
@endpush
