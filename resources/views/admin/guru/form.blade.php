<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="gelar_depan">Gelar Depan <span class="text-danger">*</span></label>
                <input id="gelar_depan" class="form-control" type="text" name="gelar_depan" autocomplete="off">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="gelar_belakang">Gelar Belakang <span class="text-danger">*</span></label>
                <input id="gelar_belakang" class="form-control" type="text" name="gelar_belakang" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input id="nama_lengkap" class="form-control" type="text" name="nama_lengkap" autocomplete="off">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input id="email" class="form-control" type="text" name="email" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                <input id="tempat_lahir" class="form-control" type="text" name="tempat_lahir" autocomplete="off">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tgl_lahir" data-target-input="nearest">
                    <input type="text" name="tgl_lahir" class="form-control datetimepicker-input"
                        data-target="#tgl_lahir" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tgl_lahir" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="nik">Nomor NIK <span class="text-danger">*</span></label>
                <input id="nik" class="form-control" type="number" name="nik" autocomplete="off">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="jenis_kelamin_id">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin_id" id="jenis_kelamin_id" class="form-control">
                    <option disabled selected>Pilih salah satu</option>
                    @foreach ($jenisKelamin as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tmt_guru">TMT Guru <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tmt_guru" data-target-input="nearest">
                    <input type="text" name="tmt_guru" class="form-control datetimepicker-input"
                        data-target="#tmt_guru" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tmt_guru" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-6">
            <div class="form-group">
                <label for="tmt_pegawai">TMT Pegawai <span class="text-danger">*</span></label>
                <div class="input-group datepicker" id="tmt_pegawai" data-target-input="nearest">
                    <input type="text" name="tmt_pegawai" class="form-control datetimepicker-input"
                        data-target="#tmt_pegawai" data-toggle="datetimepicker" autocomplete="off" />
                    <div class="input-group-append" data-target="#tmt_pegawai" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary"
            id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status"
                aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
