<form id="form-ortu" action="{{ route('siswa.update_ortu', $siswa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="nama_ayah">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                <input id="nama_ayah" class="form-control" type="text" name="nama_ayah" autocomplete="off"
                    value="{{ old('nama_ayah', $ortu->nama_ayah ?? '') }}">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pendidikan_ayah_id">Pendidikan Terakhir Ayah <span class="text-danger">*</span></label>
                <select name="pendidikan_ayah_id" id="pendidikan_ayah_id" class="form-control">
                    <option value="">-- Pilih Pendidikan Ayah --</option>
                    @if (!empty($pendidikan))
                        @foreach ($pendidikan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pendidikan_ayah_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pekerjaan_ayah_id">Pekerjaan Ayah <span class="text-danger">*</span></label>
                <select name="pekerjaan_ayah_id" id="pekerjaan_ayah_id" class="form-control">
                    <option value="">-- Pilih Pekerjaan Ayah --</option>
                    @if (!empty($pekerjaan))
                        @foreach ($pekerjaan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pekerjaan_ayah_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="nama_ayah">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                <input id="nama_ibu" class="form-control" type="text" name="nama_ibu" autocomplete="off"
                    value="{{ old('nama_ibu', $ortu->nama_ibu ?? '') }}">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pendidikan_ibu_id">Pendidikan Terakhir Ibu <span class="text-danger">*</span></label>
                <select name="pendidikan_ibu_id" id="pendidikan_ibu_id" class="form-control">
                    <option value="">-- Pilih Pendidikan Ibu --</option>
                    @if (!empty($pendidikan))
                        @foreach ($pendidikan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pendidikan_ibu_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pekerjaan_ibu_id">Pekerjaan Ibu <span class="text-danger">*</span></label>
                <select name="pekerjaan_ibu_id" id="pekerjaan_ibu_id" class="form-control">
                    <option value="">-- Pilih Pekerjaan Ibu --</option>
                    @if (!empty($pekerjaan))
                        @foreach ($pekerjaan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pekerjaan_ibu_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="nama_walimurid">Nama Lengkap Wali <span class="text-danger">*</span></label>
                <input id="nama_walimurid" class="form-control" type="text" name="nama_walimurid" autocomplete="off"
                    value="{{ old('nama_walimurid', $ortu->nama_walimurid ?? '') }}">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pendidikan_walimurid_id">Pendidikan Terakhir Wali <span class="text-danger">*</span></label>
                <select name="pendidikan_walimurid_id" id="pendidikan_walimurid_id" class="form-control">
                    <option value="">-- Pilih Pendidikan --</option>
                    @if (!empty($pendidikan))
                        @foreach ($pendidikan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pendidikan_walimurid_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="pekerjaan_walimurid_id">Pekerjaan Wali <span class="text-danger">*</span></label>
                <select name="pekerjaan_walimurid_id" id="pekerjaan_walimurid_id" class="form-control">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @if (!empty($pekerjaan))
                        @foreach ($pekerjaan as $item)
                            <option value="{{ $item->id }}"
                                {{ optional($ortu)->pekerjaan_walimurid_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>
    </div>

    {{--  <a href="{{ route('siswa.index') }}" class="btn btn-warning">Kembali</a>  --}}
    <button type="submit" id="btn-simpan" class="btn btn-primary float-right mt-2"><i class="fas fa-save"></i>
        Simpan</button>
</form>

@include('includes.datepicker')

@push('scripts')
    <script>
        function submitForm(form) {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menyimpan data?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Simpan!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        $(document).ready(function() {
            // Event saat form disubmit
            $('#form-ortu').submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman
                simpanOrtu(); // Panggil function
            });
        });

        // Function untuk menyimpan data siswa dengan SweetAlert2
        function simpanOrtu() {
            let form = $('#form-ortu')[0]; // Ambil elemen form
            let formData = new FormData(form); // Buat FormData
            let btn = $('#btn-simpan');

            btn.prop('disabled', true).text('Menyimpan...'); // Nonaktifkan tombol submit

            $.ajax({
                url: $('#form-ortu').attr('action'), // Ambil URL dari atribut action form
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val() // Tambahkan token CSRF
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
                success: function(response) {
                    btn.prop('disabled', false).text('Simpan');

                    if (response.status = 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil disimpan!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#form-ortu')[0].reset(); // Reset form
                        window.location.href = '{{ route('siswa.detail', $siswa->id) }}'
                    }
                },
                error: function(errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    }).then(() => {
                        btn.prop('disabled', false).text('Simpan');
                    });

                    if (errors.status == 422) {
                        $('#spinner-border').hide()
                        btn.prop('disabled', false).text('Simpan');

                        loopErrors(errors.responseJSON.errors);
                    }
                }
            });
        }
    </script>
@endpush
