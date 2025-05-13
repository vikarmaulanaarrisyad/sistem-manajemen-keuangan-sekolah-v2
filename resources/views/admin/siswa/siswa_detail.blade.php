<form id="form-siswa" action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-12 text-center">
            <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('AdminLTE/dist/img/avatar.png') }}"
                alt="Foto Siswa" class="rounded-circle img-thumbnail"
                style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 20px;">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nisn">NISN <span class="text-danger">*</span></label>
                <input id="nisn" class="form-control" type="text" name="nisn" autocomplete="off"
                    value="{{ old('nisn', $siswa->nisn) }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nis">NIS Lokal<span class="text-danger">*</span></label>
                <input id="nis" class="form-control" type="text" name="nis" autocomplete="off"
                    value="{{ old('nis', $siswa->nis) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="kk">No. KK <span class="text-danger">*</span></label>
                <input id="kk" class="form-control" type="text" name="kk" autocomplete="off"
                    value="{{ old('kk', $siswa->kk) }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nik">No. NIK<span class="text-danger">*</span></label>
                <input id="nik" class="form-control" type="text" name="nik" autocomplete="off"
                    value="{{ old('nik', $siswa->nik) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input id="nama_lengkap" class="form-control" type="text" name="nama_lengkap" autocomplete="off"
                    value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nama_panggilan">Nama Panggilan <span class="text-danger">*</span></label>
                <input id="nama_panggilan" class="form-control" type="text" name="nama_panggilan" autocomplete="off"
                    value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                <input id="tempat_lahir" class="form-control" type="text" name="tempat_lahir" autocomplete="off"
                    value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tgl_lahir" data-target-input="nearest">
                    <input type="text" name="tgl_lahir" class="form-control datetimepicker-input"
                        data-target="#tgl_lahir" data-toggle="datetimepicker" autocomplete="off"
                        value="{{ old('tgl_lahir', $siswa->tgl_lahir) }}" />
                    <div class="input-group-append" data-target="#tgl_lahir" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="form-group">
                <label for="kewarganegaraan_id">Kewarganegaraan <span class="text-danger">*</span></label>
                <select name="kewarganegaraan_id" id="kewarganegaraan_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($kewarganegaraan as $item)
                        <option value="{{ $item->id }}"
                            {{ $siswa->kewarganegaraan_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="jenis_kelamin_id">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin_id" id="jenis_kelamin_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($jenisKelamin as $item)
                        <option value="{{ $item->id }}"
                            {{ $siswa->jenis_kelamin_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label for="agama_id">Agama <span class="text-danger">*</span></label>
                <select name="agama_id" id="agama_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($agama as $item)
                        <option value="{{ $item->id }}" {{ $siswa->agama_id == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="jumlah_saudara">Jumlah Saudara</label>
                <input id="jumlah_saudara" class="form-control" type="number" min="0"
                    value="{{ old('jumlah_saudara', $siswa->jumlah_saudara) }}" name="jumlah_saudara">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="anakke">Anak Ke</label>
                <input id="anakke" class="form-control" type="number" min="0"
                    value="{{ old('jumlah_saudara', $siswa->jumlah_saudara) }}" name="anakke">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="alamat">Alamat Siswa</label>
                <textarea name="alamat" id="alamat" cols="2" rows="2" class="form-control">{{ $siswa->alamat }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="foto_siswa">Unggah Foto Siswa</label>
                <input type="file" id="foto_siswa" class="form-control" name="foto_siswa" accept="image/*">
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="btnAmbilWebcam">
                <i class="fas fa-camera"></i> Ambil dari Webcam
            </button>
            <button type="button" class="btn btn-success btn-sm" id="btnCapture" style="display: none;">
                <i class="fas fa-save"></i> Capture
            </button>
        </div>
        <div class="col-lg-6 text-center">
            <video id="webcam" width="100%" autoplay style="display: none;"></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <img id="foto_preview" src="" class="img-thumbnail mt-2"
                style="width: 2.5cm; height: 3.5cm; object-fit: cover; display: none;">
            <input type="hidden" name="captured_image" id="captured_image">
        </div>
    </div>
    {{--  <a href="{{ route('siswa.index') }}" class="btn btn-warning">Kembali</a>  --}}
    <button type="submit" id="btn-simpan" class="btn btn-primary float-right mt-2"><i class="fas fa-save"></i>
        Simpan</button>
</form>

@include('includes.datepicker')

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let video = document.getElementById('webcam');
            let canvas = document.getElementById('canvas');
            let fotoPreview = document.getElementById('foto_preview');
            let btnAmbilWebcam = document.getElementById('btnAmbilWebcam');
            let btnCapture = document.getElementById('btnCapture');
            let capturedImageInput = document.getElementById('captured_image');
            let fotoInput = document.getElementById('foto_siswa');
            let nisnInput = document.getElementById('nisn');

            let constraints = {
                video: true
            };

            btnAmbilWebcam.addEventListener('click', function() {
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(function(stream) {
                        video.srcObject = stream;
                        video.style.display = 'block';
                        fotoPreview.style.display = 'none';
                        btnCapture.style.display = 'inline-block';
                    })
                    .catch(function(err) {
                        Swal.fire("Error!", "Akses kamera ditolak!", "error");
                    });
            });

            btnCapture.addEventListener('click', function() {
                let context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                let nisn = nisnInput.value.trim();
                if (!nisn) {
                    Swal.fire("Perhatian!", "Harap isi NISN terlebih dahulu!", "warning");
                    return;
                }

                // Konversi ke Base64 (hanya untuk preview, bukan untuk dikirim)
                let dataURL = canvas.toDataURL('image/png');
                fotoPreview.src = dataURL;
                fotoPreview.style.display = 'block';

                // Konversi Base64 ke Blob untuk dijadikan file
                fetch(dataURL)
                    .then(res => res.blob())
                    .then(blob => {
                        let file = new File([blob], `foto_${nisn}.png`, {
                            type: "image/png"
                        });

                        // Masukkan file ke dalam input file
                        let dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fotoInput.files = dataTransfer.files;

                        // Hentikan akses kamera setelah mendapatkan gambar
                        let stream = video.srcObject;
                        let tracks = stream.getTracks();
                        tracks.forEach(track => track.stop());
                        video.srcObject = null;
                        video.style.display = 'none';
                        btnCapture.style.display = 'none';

                        Swal.fire("Berhasil!", "Gambar berhasil diambil!", "success");
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Gagal mengkonversi gambar!", "error");
                        console.error(error);
                    });
            });

        });

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
            $('#form-siswa').submit(function(e) {
                e.preventDefault(); // Mencegah reload halaman
                simpanSiswa(); // Panggil function
            });
        });

        // Function untuk menyimpan data siswa dengan SweetAlert2
        function simpanSiswa() {
            let form = $('#form-siswa')[0]; // Ambil elemen form
            let formData = new FormData(form); // Buat FormData
            let btn = $('#btn-simpan');

            btn.prop('disabled', true).text('Menyimpan...'); // Nonaktifkan tombol submit

            $.ajax({
                url: $('#form-siswa').attr('action'), // Ambil URL dari atribut action form
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
                        $('#form-siswa')[0].reset(); // Reset form
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
