<!-- Modal -->
<div class="modal fade" id="importExcelModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importExcelModalLabel">
                    <i class="fas fa-file-import"></i> Import Data dari Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi tambahan -->
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Pastikan format file sesuai dengan template yang disediakan.
                </div>

                <!-- Link Download Template -->
                <div class="mb-3">
                    <a href="{{ asset('template/template_siswa.xlsx') }}" class="btn btn-success btn-sm shadow">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>

                <!-- Form Upload -->
                <form id="uploadForm" action="{{ route('siswa.importEXCEL') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="excelFile" class="form-label fw-bold">Pilih File Excel</label>
                        <input type="file" class="form-control border-primary shadow-sm" id="excelFile"
                            name="excelFile" accept=".xlsx, .xls" required>
                        <div class="small text-muted mt-1">Hanya file dengan format .xlsx atau .xls yang diperbolehkan.
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-3 d-none" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" style="width: 0%;" id="progressBar"></div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary shadow" id="uploadBtn">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#uploadForm").on("submit", function(event) {
                event.preventDefault(); // Mencegah submit default

                let form = this;
                let progressBar = $("#progressBar");
                let uploadProgress = $("#uploadProgress");
                let fileInput = $("#excelFile");
                let uploadBtn = $("#uploadBtn");

                // Tampilkan progress bar
                uploadProgress.removeClass("d-none");
                progressBar.css("width", "0%").removeClass("bg-success bg-danger");

                // Disable tombol dan ubah teks jadi loading
                uploadBtn.prop("disabled", true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Uploading...
            `);

                let formData = new FormData(form);
                let xhr = new XMLHttpRequest();
                xhr.open("POST", form.action, true);

                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        let percentComplete = (e.loaded / e.total) * 100;
                        progressBar.css("width", percentComplete + "%");
                    }
                };

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        progressBar.addClass("bg-success");
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'File berhasil diupload.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Terjadi kesalahan saat upload!',
                            timer: 3000,
                        }).then(() => {
                            progressBar.addClass("bg-danger");
                            progressBar.css("width", "0%").removeClass("bg-success bg-danger");
                            uploadProgress.addClass("d-none");
                            fileInput.val("");
                        });
                    }
                };

                xhr.onerror = function() {
                    uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Gagal mengunggah file. Periksa koneksi internet Anda.',
                        showConfirmButton: true
                    });
                };

                xhr.send(formData);
            });

            // Reset saat modal ditutup
            $("#importExcelModal").on("hidden.bs.modal", function() {
                let form = $("#uploadForm")[0];
                let fileInput = $("#excelFile");
                let progressBar = $("#progressBar");
                let uploadProgress = $("#uploadProgress");
                let uploadBtn = $("#uploadBtn");

                setTimeout(function() {
                    form.reset();
                    fileInput.val("");
                    progressBar.css("width", "0%").removeClass("bg-success bg-danger");
                    uploadProgress.addClass("d-none");
                    uploadBtn.prop("disabled", false).html('<i class="fas fa-upload"></i> Upload');
                }, 300);
            });
        });
    </script>
@endpush
