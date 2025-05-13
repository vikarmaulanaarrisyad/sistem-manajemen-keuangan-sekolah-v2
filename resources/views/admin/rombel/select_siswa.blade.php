    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahSiswaLabel">Pilih Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table class="table-siswa" style="width: 100%">
                                <x-slot name="thead">
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NISN</th>
                                    <th><input type="checkbox" id="selectAll"></th>
                                </x-slot>
                            </x-table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button onclick="tambahSiswa()" type="button" class="btn btn-primary" id="btnTambahSiswa">Tambah
                        Siswa</button>
                </div>
            </div>
        </div>
    </div>
