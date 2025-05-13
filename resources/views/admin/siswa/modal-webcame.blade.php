<div class="modal fade" id="webcamModal" tabindex="-1" aria-labelledby="webcamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="webcamModalLabel">Ambil Foto Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <video id="webcam" width="100%" height="auto" autoplay></video>
                <canvas id="canvas" style="display: none;"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="captureImage()">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        function captureImage() {
            let video = document.getElementById('webcam');
            let canvas = document.getElementById('canvas');
            let context = canvas.getContext('2d');

            // Sesuaikan ukuran canvas dengan video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Gambar frame dari video ke canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Konversi gambar ke format base64
            let imageData = canvas.toDataURL('image/png');

            // Kirim data ke server atau tampilkan hasilnya
            alert("Foto berhasil diambil!");

            // Tutup modal dan hentikan kamera
            $('#webcamModal').modal('hide');
            stopWebcam();
        }

        function stopWebcam() {
            let video = document.getElementById('webcam');
            let stream = video.srcObject;
            let tracks = stream.getTracks();

            tracks.forEach(track => track.stop());
            video.srcObject = null;
        }

        // Hentikan webcam saat modal ditutup
        $('#webcamModal').on('hidden.bs.modal', function() {
            stopWebcam();
        });
    </script>
@endpush
