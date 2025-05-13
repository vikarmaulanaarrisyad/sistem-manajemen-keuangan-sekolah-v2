@extends('layouts.app')

@section('title', 'Tambah Rombongan Belajar')
@section('subtitle', 'Tambah Rombongan Belajar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rombel.index') }}">Rombongan Belajar</a></li>
    <li class="breadcrumb-item active">Tambah Rombongan Belajar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <h6 class="card-title"><i class="fas fa-users mr-1 mt-2"></i>@yield('subtitle')</h6>
                </x-slot>

                {{-- Form Tambah Rombel --}}
                <form id="formRombel" action="{{ route('rombel.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Rombel</label>
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kelas_id">Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kurikulum">Kurikulum</label>
                                <select name="kurikulum_id" id="kurikulum_id" class="form-control" required>
                                    <option value="">-- Pilih Kurikulum --</option>
                                    @foreach ($kurikulum as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="walikelas">Wali Kelas</label>
                                <select name="walikelas" id="walikelas" class="form-control" required>
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach ($walikelas as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('rombel.index') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function simpanRombel() {
            let form = $('#formRombel')[0];
            let formData = new FormData(form);
            let btn = $('button[type=submit]');

            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: $('#formRombel').attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan data...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response, xhr) {
                    Swal.close()
                    btn.prop('disabled', false).text('Simpan');

                    if (xhr.status === 201 || xhr.status === 200 || response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil disimpan!',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.href = '{{ route('rombel.index') }}';
                        });
                        $('#formRombel')[0].reset();
                    }
                },
                error: function(errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON.message || 'Terjadi kesalahan!',
                        showConfirmButton: true,
                    }).then(() => {
                        btn.prop('disabled', false).text('Simpan');
                    });

                    if (errors.status == 422) {
                        btn.prop('disabled', false).text('Simpan');
                        loopErrors(errors.responseJSON.errors);
                    }
                }
            });
        }

        function loopErrors(errors) {
            $.each(errors, function(key, value) {
                let input = $('[name=' + key + ']');
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">' + value[0] + '</div>');
            });
        }

        $(document).ready(function() {
            $('#formRombel').on('submit', function(e) {
                e.preventDefault();
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                simpanRombel();
            });
        });
    </script>
@endpush
