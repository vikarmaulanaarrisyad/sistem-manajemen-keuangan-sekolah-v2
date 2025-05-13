@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>

            <div class="col-md-8 col-lg-6">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-lg-8 mx-auto">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('/img/logo.png') }}" alt="" class="w-50 mb-4">
                                </a>
                                <h4 class="login-heading mb-4">Silahkan Lengkapi Form Registrasi!</h4>

                                {{-- Form --}}
                                <form id="registerForm" action="{{ route('register') }}" method="post">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" autocomplete="off">

                                        @error('name')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username') }}" autocomplete="off">

                                        @error('username')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" autocomplete="off">

                                        @error('email')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror password"
                                            id="password" name="password" autocomplete="off">

                                        @error('password')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation">Konfirmasi Password</label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" autocomplete="off">

                                        @error('password_confirmation')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div>
                                        <button type="button" onclick="daftar()" id="daftarButton"
                                            class="btn btn-lg btn-primary btn-daftar mb-2">
                                            <i class="fas fa-sign-in-alt"></i> <span class="text-sm"
                                                id="buttonText">Daftar</span>
                                            <span id="loadingSpinner" style="display:none;"><i
                                                    class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="text-muted">
                                            Sudah punya akun silahkan login
                                            <a href="{{ route('login') }}" class="text-muted">disini</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Menangani keypress event
        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                daftar();
            }
        });

        // Fungsi untuk daftar
        function daftar() {
            let username = $('#email').val();
            let password = $('.password').val();

            if (!username) {
                toastr.info('Emsil wajib diisi');
                return;
            }

            if (!password) {
                toastr.info('Password wajib diisi');
                return;
            }

            // Disable the button to prevent multiple clicks during the Ajax request
            const daftarButton = $('#daftarButton');
            const buttonText = $('#buttonText');
            const loadingSpinner = $('#loadingSpinner');

            daftarButton.attr('disabled', true);
            buttonText.hide();
            loadingSpinner.show();

            $.ajax({
                type: 'POST',
                url: '{{ route('register') }}',
                data: $('#registerForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Daftar akun berhasil',
                        text: 'Selamat anda berhasil daftar ke dalam sistem kami',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = '{{ route('dashboard') }}';
                    });
                },
                error: function(errors) {
                    // Handle the error response
                    loopErrors(errors.responseJSON.errors);
                    toastr.error(errors.responseJSON.message);
                },
                complete: function() {
                    // Re-enable the button and hide the loading indicator
                    daftarButton.attr('disabled', false);
                    buttonText.show();
                    loadingSpinner.hide();
                }
            });
        }
    </script>
@endpush
