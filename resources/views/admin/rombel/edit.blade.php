@extends('layouts.app')

@section('title', 'Edit Rombongan Belajar')
@section('subtitle', 'Edit Rombongan Belajar')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rombel.index') }}">Rombongan Belajar</a></li>
    <li class="breadcrumb-item active">Edit Rombongan Belajar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            {{-- Form Detail Rombel --}}
            <form id="formRombel" action="{{ route('rombel.update', $rombel->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Menggunakan metode PUT untuk update data -->
                <x-card>
                    <x-slot name="header">
                        <h6 class="card-title"><i class="fas fa-users mr-1 mt-2"></i>@yield('subtitle')</h6>
                    </x-slot>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Rombel</label>
                                <input type="text" name="nama" id="nama" class="form-control"
                                    value="{{ old('nama', $rombel->nama) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kelas_id">Kelas</label>
                                <select name="kelas_id" disabled id="kelas_id" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelas as $kls)
                                        <option
                                            value="{{ $kls->id }}"{{ $rombel->kelas_id == $kls->id ? 'selected' : '' }}>
                                            {{ $kls->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kurikulum">Kurikulum</label>
                                <select name="kurikulum_id" disabled id="kurikulum_id" class="form-control" required>
                                    <option value="">-- Pilih Kurikulum --</option>
                                    @foreach ($kurikulum as $item)
                                        <option
                                            value="{{ $item->id }}"{{ $rombel->kurikulum_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
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
                                        <option
                                            value="{{ $guru->id }}"{{ $rombel->wali_kelas_id == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Button Tambah Siswa --}}
                    <div class="text-right mb-2">
                        <button onclick="modalTambahSiswa()" type="button" class="btn btn-success btn-sm">
                            <i class="fas fa-plus-circle"></i> Tambah Siswa
                        </button>
                    </div>

                    <x-table class="rombel-siswa">
                        <x-slot name="thead">
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NISN</th>
                            <th>NIS</th>
                            <th>Aksi</th>
                        </x-slot>
                    </x-table>

                    <x-slot name="footer">
                        <a href="{{ route('rombel.index') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-primary"
                            id="submitBtn">
                            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            <i class="fas fa-save mr-1"></i>
                            Simpan
                        </button>
                    </x-slot>
                </x-card>
            </form>
        </div>
    </div>

    @include('admin.rombel.select_siswa')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table1, table2;
        let modal = '#modalTambahSiswa';

        table1 = $('.rombel-siswa').DataTable({
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('rombel.getSiswaRombel', $rombel->id) }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'nis'
                },
                {
                    data: 'aksi',
                    sortable: false,
                    searchable: false
                },
            ],
            dom: 'Brt',
            bSort: false,
        });

        table2 = $('.table-siswa').DataTable({
            serverSide: true,
            autoWidth: false,
            responsive: true,
            paging: false, // Tetap aktifkan pagination agar pengguna bisa memilih
            pageLength: 50,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('rombel.getDataSiswa', $rombel->id) }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'nama_lengkap'
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'aksi',
                    sortable: false,
                    searchable: false
                },
            ],
            dom: 'Brt',
            bSort: false,
        });


        function modalTambahSiswa() {
            $(modal).modal('show');
        }

        // Checkbox "Select All"
        $('#selectAll').on('click', function() {
            $('.select-siswa').prop('checked', $(this).prop('checked'));
        });

        // Jika salah satu checkbox siswa tidak dicentang, hapus centang dari "Select All"
        $(document).on('click', '.select-siswa', function() {
            if ($('.select-siswa:checked').length === $('.select-siswa').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });

        $(document).ready(function() {
            // Event listener untuk Select All
            $("#selectAll").change(function() {
                $(".select-siswa").prop("checked", this.checked);
            });

            // Fungsi untuk menambahkan siswa
            function tambahSiswa() {
                let selectedSiswa = [];
                let rombelId = '{{ $rombel->id }}'

                // Ambil semua checkbox yang dipilih
                $(".select-siswa:checked").each(function() {
                    selectedSiswa.push($(this).val());
                });

                // Validasi: Pastikan ada yang dipilih
                if (selectedSiswa.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Silakan pilih minimal satu siswa!",
                    });
                    return;
                }

                // Tampilkan loading Swal
                Swal.fire({
                    title: "Menambahkan Siswa...",
                    text: "Mohon tunggu sebentar",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim data dengan AJAX
                $.ajax({
                    url: "{{ route('rombel.addSiswa') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        siswa_ids: selectedSiswa,
                        rombel_id: rombelId
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: "Siswa berhasil ditambahkan.",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table1.ajax.reload();
                            table2.ajax.reload();
                            $('#selectAll').prop('checked', false);
                            $("#modalTambahSiswa").modal("hide"); // Tutup modal
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal!",
                                text: "Terjadi kesalahan saat menambahkan siswa.",
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Terjadi kesalahan jaringan. Silakan coba lagi!",
                        });
                    }
                });
            }

            // Event listener tombol Tambah Siswa
            $("#btnTambahSiswa").on("click", function() {
                tambahSiswa();
            });
        });

        function hapusSiswa(siswaId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Siswa ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Menghapus Siswa...",
                        text: "Mohon tunggu sebentar",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim request DELETE via AJAX
                    $.ajax({
                        url: "{{ route('siswa.rombel.delete') }}",
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            siswa_id: siswaId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: "Siswa berhasil dihapus.",
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Reload atau hapus elemen dari tabel
                                // $("#row_" + siswaId).remove();

                                table1.ajax.reload();
                                table2.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: "Terjadi kesalahan saat menghapus siswa.",
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Terjadi kesalahan jaringan. Silakan coba lagi!",
                            });
                        }
                    });
                }
            });
        }

        // Tambahkan event listener untuk tombol hapus yang ada di dalam tabel
        $(document).on("click", ".btn-hapus-siswa", function() {
            let siswaId = $(this).data("id");
            hapusSiswa(siswaId);
        });

        function submitForm(originalForm) {
            let button = $(originalForm).find("button[type=submit]");
            let modal = $(originalForm).closest(".modal");
            let table = $("#dataTable").DataTable(); // Pastikan ID tabel benar

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
                            table1.ajax.reload(); // Reload DataTables
                            table2.ajax.reload(); // Reload DataTables
                            window.location.reload();
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

        // Fungsi untuk menampilkan error validasi
        function loopErrors(errors) {
            $(".is-invalid").removeClass("is-invalid");
            $(".invalid-feedback").remove();

            $.each(errors, function(key, value) {
                let input = $("[name='" + key + "']");
                input.addClass("is-invalid");

                let errorFeedback = $("<div>").addClass("invalid-feedback").text(value[0]);
                input.after(errorFeedback);
            });
        }
    </script>
@endpush
