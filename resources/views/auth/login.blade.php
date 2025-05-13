@extends('layouts.guest')

@section('title', 'Login')

@php
    $aplikasi = \App\Models\Aplikasi::first();
@endphp

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
                                    @if ($aplikasi->logo_login != null)
                                        <img src="{{ Storage::url($aplikasi->logo_login) }}" alt=""
                                            class="w-50 mb-4">
                                    @else
                                        <div style="display: flex; justify-content: center;">
                                            <img style="width: 50%;" src="{{ asset('images/logo-madrasah1.png') }}"
                                                alt="" class="mb-4">
                                        </div>
                                    @endif

                                </a>
                                {{--  <h4 class="login-heading mb-4">Selamat Datang Kembali!</h4>  --}}
                                <h3 class="login-heading mb-2 text-center text-success">{{ $aplikasi->singkatan }}</h3>
                                <p class="text-success">{{ $aplikasi->nama }}</p>

                                {{-- Form --}}
                                <form id="loginForm" action="{{ route('login') }}" method="post">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="auth">Username</label>
                                        <input type="text" class="form-control @error('auth') is-invalid @enderror"
                                            id="auth" name="auth" value="{{ old('auth') }}" autocomplete="off"
                                            onfocus=this.value=''>

                                        @error('auth')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror password"
                                            id="password" name="password" onfocus=this.value='' autocomplete="off">

                                        @error('password')
                                            <span class="invalid-feedback">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex justify-content-between align-items-center mb-3">
                                        <div class="custom-control custom-checkbox" id="showPassword">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label for="customCheck1" class="custom-control-label" id="showPassword">Show
                                                Password</label>
                                        </div>
                                        {{--  <a href="#" class="small mt-1 text-muted">Lupa Password?</a>  --}}
                                    </div>

                                    <div>
                                        <button type="button" onclick="login()" id="loginButton"
                                            class="btn btn-lg btn-primary btn-login mb-2">
                                            <i class="fas fa-sign-in-alt"></i> <span id="buttonText">Masuk</span>
                                            <span id="loadingSpinner" style="display:none;"><i
                                                    class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div class="text-muted">
                                            {{ $aplikasi->copyright }}
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
                login();
            }
        });

        // Fungsi untuk login
        function login() {
            let auth = $('#auth').val();
            let password = $('.password').val();

            if (!auth) {
                toastr.info('Emsil wajib diisi');
                return;
            }

            if (!password) {
                toastr.info('Password wajib diisi');
                return;
            }

            // Disable the button to prevent multiple clicks during the Ajax request
            const loginButton = $('#loginButton');
            const buttonText = $('#buttonText');
            const loadingSpinner = $('#loadingSpinner');

            loginButton.attr('disabled', true);
            buttonText.hide();
            loadingSpinner.show();

            $.ajax({
                type: 'POST',
                url: '{{ route('login') }}',
                data: $('#loginForm').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login berhasil',
                        text: 'Selamat anda berhasil login ke dalam sistem kami',
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
                    loginButton.attr('disabled', false);
                    buttonText.show();
                    loadingSpinner.hide();
                }
            });
        }
    </script>
@endpush
