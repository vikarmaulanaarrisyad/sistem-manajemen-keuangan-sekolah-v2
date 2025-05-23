@extends('layouts.app')

@section('title', 'Aplikasi Madrasah')

@section('subtitle', 'Aplikasi Madrasah')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Aplikasi Madrasah</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form id="applicationForm" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama', $aplikasi->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="singkatan">Singkatan</label>
                        <input type="text" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan"
                            name="singkatan" value="{{ old('singkatan', $aplikasi->singkatan) }}" required>
                        @error('singkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="copyright">Copyright</label>
                        <input type="text" class="form-control @error('copyright') is-invalid @enderror" id="copyright"
                            name="copyright" value="{{ old('copyright', $aplikasi->copyright) }}" required>
                        @error('copyright')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Other fields remain the same -->
                    {{--  <div class="form-group">
                        <label for="logo_header">Logo Header</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo_header"
                            name="logo_header">
                        @if ($aplikasi->logo_header)
                            <img src="{{ Storage::url($aplikasi->logo_header) }}" alt="Logo" width="100"
                                class="mt-2">
                        @endif
                        @error('logo_header')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>  --}}

                    <!-- Other fields remain the same -->
                    <div class="form-group">
                        <label for="logo">Logo Login</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                            name="logo">
                        @if ($aplikasi->logo_login)
                            <img src="{{ Storage::url($aplikasi->logo_login) }}" alt="Logo" width="100"
                                class="mt-2">
                        @endif
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#applicationForm').on('submit', function(e) {
                e.preventDefault();

                // Show SweetAlert loading
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // AJAX request
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('aplikasi.update', $aplikasi->id) }}", // Update the route as needed
                    method: "POST", // Use POST instead of PUT because Laravel uses POST for method spoofing
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close(); // Close the loading
                        if (response.success) {
                            Swal.fire(
                                'Success!',
                                'Data has been successfully updated.',
                                'success'
                            ).then(() => {
                                window.location
                                    .reload(); // Optional: reload the page after success
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue with your submission.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Close the loading
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again later.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush
