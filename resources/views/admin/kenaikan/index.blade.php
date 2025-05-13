@extends('layouts.app')

@section('title', 'Data Kenaikan Siswa')

@section('subtitle', 'Proses Kenaikan Siswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Kenaikan Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Proses Kenaikan Siswa</div>
                <div class="card-body">
                    <form id="formKenaikan">
                        @csrf
                        <div class="form-group">
                            <label>Tahun Pelajaran Sebelumnya</label>
                            <input type="text" class="form-control"
                                value="{{ $tahunSebelumnya->nama }} {{ $tahunSebelumnya->semester->nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tahun Pelajaran Berikutnya (Otomatis)</label>
                            <input type="text" class="form-control"
                                value="{{ $tahunPelajaranAktif->nama }} {{ $tahunPelajaranAktif->semester->nama }}"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label>Rombel Tahun Sebelumnya</label>
                            <select class="form-control" name="rombel_sebelumnya" id="rombel_sebelumnya">
                                <option value="">-- Pilih Rombel --</option>
                                @foreach ($rombelSebelumnya as $r)
                                    <option value="{{ $r->id }}" data-kelas="{{ $r->kelas->nama }}"
                                        data-tingkat="{{ $r->kelas->tingkat }}">
                                        {{ $r->kelas->nama }} {{ $r->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas Tujuan</label>
                            <input type="text" class="form-control" name="kelas_tujuan" id="kelas_tujuan" readonly>
                        </div>

                        <div class="form-group">
                            <label>Daftar Siswa</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Nama</th>
                                        <th>NISN</th>
                                        <th>NIS</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodySiswa">
                                    {{-- Data siswa akan diisi melalui AJAX --}}
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">Proses Kenaikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        $(document).ready(function() {
            // Ambil tingkat tertinggi dari server
            const tingkatTertinggi = 6; // Misalnya, tingkat tertinggi adalah 6 (kelas 6)

            // Event saat rombel sebelumnya berubah
            $('#rombel_sebelumnya').change(function() {
                let selectedRombel = $(this).find(':selected');
                let tingkatSaatIni = parseInt(selectedRombel.data('tingkat')); // Ambil tingkat kelas
                let kelasTujuan = "";

                if (tingkatSaatIni >= tingkatTertinggi) {
                    kelasTujuan = "Lulus";
                } else {
                    kelasTujuan = "Kelas " + (tingkatSaatIni + 1); // Contoh: Kelas 5 → Kelas 6
                }

                $('#kelas_tujuan').val(kelasTujuan); // Set kelas tujuan
            });

            // AJAX untuk menampilkan siswa berdasarkan rombel
            $('#rombel_sebelumnya').change(function() {
                let rombelId = $(this).val();

                $('#tbodySiswa').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

                $.ajax({
                    url: "{{ route('kenaikan-siswa.get-siswa') }}",
                    type: "GET",
                    data: {
                        rombel_id: rombelId
                    },
                    success: function(response) {
                        let rows = "";
                        if (response.siswa.length > 0) {
                            $.each(response.siswa, function(index, siswa) {
                                rows += `
                                    <tr>
                                        <td><input type="checkbox" name="siswa_ids[]" value="${siswa.id}"></td>
                                        <td>${siswa.nama_lengkap}</td>
                                        <td>${siswa.nisn}</td>
                                        <td>${siswa.nis}</td>
                                    </tr>`;
                            });
                        } else {
                            rows =
                                '<tr><td colspan="5" class="text-center">Tidak ada siswa di rombel ini</td></tr>';
                        }
                        $('#tbodySiswa').html(rows);
                    },
                    error: function(xhr) {
                        $('#tbodySiswa').html(
                            '<tr><td colspan="5" class="text-center text-danger">Gagal mengambil data!</td></tr>'
                        );
                    }
                });
            });

            // Pilih semua siswa
            $('#selectAll').change(function() {
                $('input[name="siswa_ids[]"]').prop('checked', $(this).prop('checked'));
            });

            // Proses kenaikan siswa
            $('#formKenaikan').submit(function(e) {
                e.preventDefault();

                let selectedSiswa = $('input[name="siswa_ids[]"]:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedSiswa.length === 0) {
                    Swal.fire('Peringatan!', 'Pilih minimal satu siswa!', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data siswa akan dinaikkan ke tahun berikutnya.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('kenaikan-siswa.proses') }}",
                            type: "POST",
                            data: $('#formKenaikan').serialize(),
                            success: function(response) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                window.location.reload();
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
