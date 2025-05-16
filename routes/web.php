<?php

use App\Http\Controllers\{
    AplikasiController,
    DashboardController,
    GuruController,
    KelasController,
    KenaikanSiswaController,
    KurikulumController,
    PemasukanBosController,
    PengeluaranBosController,
    PermissionController,
    PermissionGroupController,
    RoleController,
    RombelController,
    SekolahController,
    SiswaController,
    TahunPelajaranController,
    UserController,
    UserProfileInformationController,
};
use App\Http\Controllers\Guru\SetorTabunganController;
use App\Http\Controllers\Guru\TarikTabunganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/manifest.json', function () {
    $env = env('APP_ENV_TYPE', 'production');

    // Tentukan warna berdasarkan environment
    $backgroundColor = $env === 'staging' ? '#ffeb3b' : '#6777ef';  // Contoh warna untuk staging (kuning) dan production (biru)
    $themeColor = $env === 'staging' ? '#ffeb3b' : '#6777ef';

    return response()->json([
        'name' => $env === 'staging' ? 'SIKEU Staging' : 'SIKEU Apps',
        'short_name' => env('APP_SHORT_NAME', 'SIKEU'),
        'start_url' => '/index.php',
        'background_color' => $backgroundColor,
        'description' => env('APP_DESCRIPTION'),
        'display' => 'fullscreen',
        'theme_color' => $themeColor,
        'env_type' => $env,
        'icons' => [
            [
                'src' => asset('logo.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ]
        ],
    ])->header('Content-Type', 'application/manifest+json');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Role Admin
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        //Route Tahun Pelajaran
        Route::get('/tahunpelajaran/data', [TahunPelajaranController::class, 'data'])->name('tahunpelajaran.data');
        Route::resource('/tahunpelajaran', TahunPelajaranController::class)->except('create', 'edit');
        Route::put('/tahunpelajaran/update-status/{id}', [TahunPelajaranController::class, 'updateStatus'])->name('tahunpelajaran.update_status');

        // Route Kurikulum
        Route::get('/kurikulum/data', [KurikulumController::class, 'data'])->name('kurikulum.data');
        Route::resource('/kurikulum', KurikulumController::class)->except('create', 'edit');

        // Route Guru
        Route::get('/guru/data', [GuruController::class, 'data'])->name('guru.data');
        Route::get('/guru/export-excel', [GuruController::class, 'exportEXCEL'])->name('guru.exportEXCEL');
        Route::post('/guru/import-excel', [GuruController::class, 'importEXCEL'])->name('guru.importEXCEL');
        Route::resource('/guru', GuruController::class)->except('create', 'edit');

        // Route Kelas
        Route::get('/ajax/kelas/data', [KelasController::class, 'getkelas'])->name('kelas.get');
        Route::get('/kelas/data', [KelasController::class, 'data'])->name('kelas.data');
        Route::resource('/kelas', KelasController::class)->except('edit', 'create');

        // Route Siswa
        Route::get('/siswa/data', [SiswaController::class, 'data'])->name('siswa.data');
        Route::get('/siswa/export-excel', [SiswaController::class, 'exportEXCEL'])->name('siswa.exportEXCEL');
        Route::post('/siswa/import-excel', [SiswaController::class, 'importEXCEL'])->name('siswa.importEXCEL');
        Route::resource('/siswa', SiswaController::class)->except('edit', 'create');
        Route::get('/siswa/{id}/detail', [SiswaController::class, 'detail'])->name('siswa.detail');
        Route::post('/siswa/orangtua/update', [SiswaController::class, 'updateOrtu'])->name('siswa.update_ortu');

        // Route Proses Kenaikan Siswa
        Route::get('/kenaikan-siswa', [KenaikanSiswaController::class, 'index'])->name('kenaikan-siswa.index');
        Route::get('/kenaikan-siswa/get-siswa', [KenaikanSiswaController::class, 'getSiswa'])->name('kenaikan-siswa.get-siswa');
        Route::post('/kenaikan-siswa/proses', [KenaikanSiswaController::class, 'prosesKenaikan'])->name('kenaikan-siswa.proses');
        Route::post('/kenaikan-siswa/batal', [KenaikanSiswaController::class, 'batalKenaikan'])->name('kenaikan-siswa.batal');

        Route::get('/naikkan-siswa/{rombel_id}', [SiswaController::class, 'naikkanSiswaPerRombel'])->name('siswa,kenaikanSiswa');
        Route::get('/batalkan-kenaikan/{rombel_id}', [SiswaController::class, 'batalkanKenaikanPerRombel'])->name('siswa.batalkanKenaikkanSiswa');

        // Route Rombel
        Route::get('/rombel/data', [RombelController::class, 'data'])->name('rombel.data');
        Route::resource('/rombel', RombelController::class);
        Route::get('/rombel/{rombel_id}/siswa', [RombelController::class, 'getDataSiswa'])->name('rombel.getDataSiswa');
        Route::get('/rombel/{rombel_id}/siswa/data', [RombelController::class, 'getSiswaRombel'])->name('rombel.getSiswaRombel');
        Route::post('/rombel/add-siswa', [RombelController::class, 'addSiswa'])->name('rombel.addSiswa');
        Route::delete('/siswa/rombel/delete', [RombelController::class, 'removeSiswa'])->name('siswa.rombel.delete');

        // Route Sekolah
        Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah.index');
        Route::put('/sekolah/{id}/update', [SekolahController::class, 'update'])->name('sekolah.update');
        Route::get('/aplikasi', [AplikasiController::class, 'index'])->name('aplikasi.index');
        Route::put('/aplikasi/{id}/update', [AplikasiController::class, 'update'])->name('aplikasi.update');
        Route::get('/user/profile', [UserProfileInformationController::class, 'show'])
            ->name('profile.show');

        // Manajemen User
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::get('/users/role_search', [UserController::class, 'roleSearch'])->name('users.role_search');
        Route::post('users/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::resource('/users', UserController::class);

        // Role
        Route::controller(RoleController::class)->group(function () {
            Route::get('/role/data', 'data')->name('role.data');
            Route::get('/role', 'index')->name('role.index');
            Route::get('/role/{role}/detail', 'detail')->name('role.detail');
            Route::get('/role/{role}', 'edit')->name('role.edit');
            Route::put('/role/{role}/update', 'update')->name('role.update');
            Route::post('/role', 'store')->name('role.store');
            Route::delete('/role/{role}/destroy', 'destroy')->name('role.destroy');
        });

        Route::controller(PermissionController::class)->group(function () {
            Route::get('/permissions/data', 'data')->name('permission.data');
            Route::get('/permissions', 'index')->name('permission.index');
            Route::get('/permissions/{permission}/detail', 'detail')->name('permission.detail');
            Route::get('/permissions/{permission}', 'edit')->name('permission.edit');
            Route::put('/permissions/{permission}/update', 'update')->name('permission.update');
            Route::post('/permissions', 'store')->name('permission.store');
            Route::delete('/permissions/{permission}/destroy', 'destroy')->name('permission.destroy');
        });

        Route::controller(PermissionGroupController::class)->group(function () {
            Route::get('/permissiongroups/data', 'data')->name('permissiongroups.data');
            Route::get('/permissiongroups', 'index')->name('permissiongroups.index');
            Route::get('/permissiongroups/{permissionGroup}/detail', 'detail')->name('permissiongroups.detail');
            Route::get('/permissiongroups/{permissionGroup}', 'edit')->name('permissiongroups.edit');
            Route::put('/permissiongroups/{permissionGroup}/update', 'update')->name('permissiongroups.update');
            Route::post('/permissiongroups', 'store')->name('permissiongroups.store');
            Route::delete('/permissiongroups/{permissionGroup}/destroy', 'destroy')->name('permissiongroups.destroy');
        });
    });

    // Role Guru
    Route::group(['middleware' => 'role:guru|bendahara', 'prefix' => 'guru'], function () {
        Route::group(['middleware' => 'permission:read-tabungan-siswa'], function () {
            // Tabungan
            Route::get('/tabungan/setor/data', [SetorTabunganController::class, 'data'])->name('setor.data');
            Route::get('/tabungan/setor/getsiswa', [SetorTabunganController::class, 'getSiswa'])->name('setor.getSiswa');
            Route::get('/tabungan/setor', [SetorTabunganController::class, 'index'])->name('setor.index');
            Route::post('/tabungan/setor/simpan', [SetorTabunganController::class, 'store'])->name('setor.store');
            Route::get('/tabungan/setor/{id}', [SetorTabunganController::class, 'show'])->name('setor.show');
            Route::get('/tabungan/setor/{id}/pdf', [SetorTabunganController::class, 'PDF'])->name('setor.downloadPDF');
            Route::put('/tabungan/setor/{id}', [SetorTabunganController::class, 'update'])->name('setor.update');
            Route::delete('/tabungan/setor/{id}', [SetorTabunganController::class, 'destroy'])->name('setor.destroy');

            Route::get('/tabungan/tarik/data', [TarikTabunganController::class, 'data'])->name('tarik.data');
            Route::get('/tabungan/tarik/getsiswa', [TarikTabunganController::class, 'getSiswa'])->name('tarik.getSiswa');
            Route::get('/tabungan/tarik', [TarikTabunganController::class, 'index'])->name('tarik.index');
            Route::post('/tabungan/tarik/simpan', [TarikTabunganController::class, 'store'])->name('tarik.store');
            Route::get('/tabungan/tarik/{id}', [TarikTabunganController::class, 'show'])->name('tarik.show');
            Route::get('/tabungan/tarik/{id}/pdf', [TarikTabunganController::class, 'PDF'])->name('tarik.downloadPDF');
            Route::put('/tabungan/tarik/{id}', [TarikTabunganController::class, 'update'])->name('tarik.update');
            Route::delete('/tabungan/tarik/{id}', [TarikTabunganController::class, 'destroy'])->name('tarik.destroy');
        });

        // Pemasukan (hanya jika punya permission)
        Route::group(['middleware' => 'permission:read-keuangan-sekolah'], function () {
            Route::get('/pemasukan/data', [PemasukanBosController::class, 'data'])->name('pemasukan.data');
            Route::get('/pemasukan/get-siswa', [PemasukanBosController::class, 'getSiswa'])->name('pemasukan.getSiswa');
            Route::put('/pemasukan/update-status/{id}', [PemasukanBosController::class, 'updateStatus'])->name('pemasukan.update_status');
            Route::resource('/pemasukan', PemasukanBosController::class);

            Route::get('/pengeluaran/data', [PengeluaranBosController::class, 'data'])->name('pengeluaran.data');
            Route::resource('/pengeluaran', PengeluaranBosController::class);
        });
    });
});
