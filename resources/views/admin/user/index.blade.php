@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('subtitle', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Kelas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title">
                        @yield('subtitle')
                    </h3>
                    <div class="card-tools">
                        <div class="d-flex align-items-center">
                            <div>

                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Foto</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Aksi</th>
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
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('users.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'foto'
                },
                {
                    data: 'username'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        })

        function resetPassword(userId) {
            Swal.fire({
                title: 'Reset Password?',
                text: "Apakah Anda yakin ingin mereset password pengguna ini ke default?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mohon Tunggu...',
                        text: 'Sedang memproses reset password',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: `{{ route('users.resetPassword', '') }}/${userId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Password telah direset ke default.',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            table.ajax.reload(); // Reload tabel pengguna
                        },
                        error: function(xhr) {
                            Swal.close();
                            let errorMessage = "Terjadi kesalahan!";
                            if (xhr.responseJSON?.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops! Gagal',
                                text: errorMessage,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
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
