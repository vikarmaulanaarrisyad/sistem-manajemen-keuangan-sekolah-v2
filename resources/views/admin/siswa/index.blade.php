@extends('layouts.app')

@section('title', 'Data Siswa')

@section('subtitle', 'Data Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-left-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-danger">🚨 Perhatian! Siswa Belum Masuk Rombel</h5>
                            <p class="mb-2 text-dark">
                                Ada siswa yang belum dimasukkan ke dalam **rombongan belajar**. Pastikan semua siswa sudah
                                memiliki rombel untuk menghindari kesalahan dalam administrasi akademik.
                            </p>
                            <p class="mb-0">
                                Silakan lakukan pembaruan melalui menu berikut:
                                <a href="{{ route('rombel.index') }}"
                                    class="btn btn-danger btn-sm font-weight-bold shadow">Kelola Rombel</a>
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
                        @yield('subtitle')
                    </h3>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div>
                                <button onclick="confirmImport()" type="button" class="btn btn-success btn-sm"><i
                                        class="fas fa-download"></i> Import
                                    Siswa</button>

                                <button onclick="addForm(`{{ route('siswa.store') }}`)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Foto</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Rombel</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.siswa.form')
    @include('admin.siswa.import-excel')
    @include('admin.siswa.modal-webcame')
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
            lengthMenu: [
                [10, 30, 50, -1],
                [10, 30, 50, "Semua"]
            ],
            pageLength: 30,
            ajax: {
                url: '{{ route('siswa.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'foto',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nisn'
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
                    data: 'rombel'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function addForm(url, title = 'Form Siswa') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');

            resetForm(`${modal} form`);
        }

        function editForm(url, title = 'Form Siswa') {
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

                    // Setel gambar preview jika ada foto
                    if (response.data.foto) {
                        $('#foto_preview').attr('src', response.data.foto).show();
                    } else {
                        $('#foto_preview').hide(); // Sembunyikan jika tidak ada foto
                    }
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

        function confirmImport() {
            $(importExcel).modal('show');
        }
    </script>
@endpush
