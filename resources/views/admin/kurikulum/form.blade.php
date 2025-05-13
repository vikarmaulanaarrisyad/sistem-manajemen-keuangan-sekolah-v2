<!-- Modal Tambah Kurikulum -->
<x-modal id="modalTambahKurikulum" data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">Tambah Kurikulum</x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kurikulum_lama">Pilih Kurikulum Lama<span class="text-danger">*</span></label>
                <select name="kurikulum_lama" id="kurikulum_lama" class="form-control">
                    <option value"pilih">Pilih salah satu</optionva>
                    <option value="copy">Tambah dari Tahun Pelajaran Sebelumnya</option>
                    <option value="">Kurikulum Baru</option>
                </select>
            </div>
        </div>
    </div>

    <input id="nama_kurikulum_lama" type="hidden" name="nama_kurikulum_lama">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kurikulum_baru">Nama Kurikulum Baru <span class="text-danger">*</span></label>
                <input id="kurikulum_baru" class="form-control" type="text" name="nama"
                    placeholder="Nama kurikulum" disabled>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>

<!-- Modal Pilih Kurikulum Lama -->
<x-modal id="modalPilihKurikulumLama" size="modal-md">
    <x-slot name="title">Pilih Kurikulum Tahun Sebelumnya</x-slot>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="kurikulum_sebelumnya">Pilih Kurikulum:</label>
                <select id="kurikulum_sebelumnya" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($kurikulums as $kurikulum)
                        <option value="{{ $kurikulum->nama }}">{{ $kurikulum->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-sm btn-outline-primary" id="pilihKurikulumBtn">
            <i class="fas fa-check"></i> Pilih
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Batal
        </button>
    </x-slot>
</x-modal>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Ketika dropdown berubah
            $('#kurikulum_lama').change(function() {
                let selectedValue = $(this).val();

                if (selectedValue === "copy") {
                    // Jika memilih "Tambah dari Tahun Pelajaran Sebelumnya"
                    $('#modalTambahKurikulum').modal('hide');
                    $('#modalPilihKurikulumLama').modal('show');
                } else if (selectedValue === "") {
                    // Jika memilih "Kurikulum Baru", input diaktifkan
                    $('#kurikulum_baru').val('').prop('disabled', false).focus();
                    $('[name=nama_kurikulum_lama]').val('');
                } else {
                    // Jika memilih kurikulum lama lainnya
                    $('#kurikulum_baru').val('').prop('disabled', false);
                }
            });

            // Saat tombol "Pilih" di modal kedua ditekan
            $('#pilihKurikulumBtn').click(function() {
                let selectedKurikulum = $('#kurikulum_sebelumnya').val();

                if (selectedKurikulum) {
                    // $('#kurikulum_baru').val(selectedKurikulum).prop('disabled', true);
                    $('[name=nama]').val(selectedKurikulum).prop('disabled', true);
                    $('[name=nama_kurikulum_lama]').val(selectedKurikulum);
                    $('#kurikulum_lama').val('copy');
                    $('#modalPilihKurikulumLama').modal('hide');
                    $('#modalTambahKurikulum').modal('show');
                }
            });

            // Jika mengetik di inputan, dropdown dinonaktifkan
            $('#kurikulum_baru').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('#kurikulum_lama').val('').prop('disabled', true);
                } else {
                    $('#kurikulum_lama').prop('disabled', false);
                }
            });
        });
    </script>
@endpush
