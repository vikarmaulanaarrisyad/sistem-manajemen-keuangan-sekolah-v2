<aside class="main-sidebar elevation-4 sidebar-light-success">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-success">
        @if ($sekolah->logo != null)
            <img src="{{ Storage::url($sekolah->logo) ?? '' }}" alt="Logo"
                class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $aplikasi->singkatan }}</span>
        @else
            <img src="{{ asset('images/logo-madrasah1.png') }}" alt="Logo"
                class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $aplikasi->singkatan }}</span>
        @endif

    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!empty(auth()->user()->foto) && Storage::disk('public')->exists(auth()->user()->foto))
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="logo" class="img-circle elevation-2"
                        style="width: 35px; height: 35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block" data-toggle="tooltip" data-placement="top"
                    title="Edit Profil">
                    {{ auth()->user()->name }}
                    <i class="fas fa-pencil-alt ml-2 text-sm text-primary"></i>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if (Auth::user()->hasRole('admin'))
                    <li class="nav-header">MASTER DATA</li>
                    @can('read-tahun-pelajaran')
                        <li class="nav-item">
                            <a href="{{ route('tahunpelajaran.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Tahun Pelajaran
                                </p>
                            </a>
                        </li>
                    @endcan

                    @can('read-kurikulum')
                        <li class="nav-item">
                            <a href="{{ route('kurikulum.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>Kurikulum</p>
                            </a>
                        </li>
                    @endcan

                    @can('read-gtk')
                        <li class="nav-item">
                            <a href="{{ route('guru.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>
                                    GTK
                                </p>
                            </a>
                        </li>
                    @endcan

                    @can('read-kelas')
                        <li class="nav-item">
                            <a href="{{ route('kelas.index') }}" class="nav-link">
                                <i class="nav-icon fab fa-instalod"></i>
                                <p>
                                    Kelas
                                </p>
                            </a>
                        </li>
                    @endcan

                    @can('read-kelas')
                        <li class="nav-item">
                            <a href="{{ route('siswa.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Siswa
                                </p>
                            </a>
                        </li>
                    @endcan

                    @php
                        // Ambil tahun pelajaran aktif
                        $tahunPelajaranAktif = \App\Models\TahunPelajaran::aktif()->first();

                        // Ambil tahun sebelumnya yang memiliki semester "Genap"
                        $tahunSebelumnya = $tahunPelajaranAktif
                            ? \App\Models\TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
                                ->whereHas('semester', function ($query) {
                                    $query->where('nama', 'Genap');
                                })
                                ->orderBy('id', 'desc')
                                ->first()
                            : null;
                    @endphp
                    @if ($tahunSebelumnya)
                        <li class="nav-item">
                            <a href="{{ route('kenaikan-siswa.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>Proses Kenaikan</p>
                            </a>
                        </li>
                    @endif
                    @can('read-rombel')
                        <li class="nav-item">
                            <a href="{{ route('rombel.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Rombongan Belajar
                                </p>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-header">MANAGEMEN PENGGUNA</li>
                    {{--  <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Managemen User</p>
                    </a>
                </li>  --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Managemen User</p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none">
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('role.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Role</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permission</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('permissiongroups.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Group Permission</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">PENGATURAN</li>
                    <li class="nav-item">
                        <a href="{{ route('sekolah.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Sekolah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('aplikasi.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Aplikasi</p>
                        </a>
                    </li>
                    <li class="nav-item mb-5">
                        <a href="#" class="nav-link" onclick="document.querySelector('#form-logout').submit()">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>

                            <form action="{{ route('logout') }}" method="post" id="form-logout">
                                @csrf
                            </form>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->hasRole('guru') || Auth::user()->hasRole('bendahara'))
                    @can('read-tabungan-siswa')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-credit-card"></i>
                                <p>
                                    Tabungan Siswa
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('setor.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Setor Tunai</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tarik.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tarik Tunai</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('read-keuangan-sekolah')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-money-check"></i>
                                <p>
                                    Keuangan Sekolah
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('pemasukan.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pemasukan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pengeluaran.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengeluaran</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                @endif
            </ul>
        </nav>
    </div>
</aside>
