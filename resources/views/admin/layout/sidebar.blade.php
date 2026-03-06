<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="logo-link nk-sidebar-logo d-flex align-items-center">
            <img src="{{ asset('public/admin_assets/images/logosekolah.png') }}" style="height: 67px;">
            </a>
            <img class="mt-3 ms-n2" style="height:200px;" src="{{ asset('public/admin_assets/images/tulisanlogo2.png') }}" alt="Tulisan Logo">
        </div>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Menu</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="{{ route('dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>


                    @if (auth()->check() && auth()->user()->role_id == 2)
                        <li class="nk-menu-item">
                            <a href="{{ route('users.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-list-fill"></em></span>
                                <span class="nk-menu-text">Manajemen Pengguna</span>
                            </a>
                    @endif


                    @if (auth()->check() && in_array(auth()->user()->role_id, [2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('mata_pelajaran.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-book-fill"></em></span>
                                <span class="nk-menu-text">Mata Pelajaran</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && in_array(auth()->user()->role_id, [2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('guru_profiles.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                                <span class="nk-menu-text">Profil Guru</span>
                            </a>
                        </li>
                    @endif


                    @if (auth()->check() && in_array(auth()->user()->role_id, [1, 2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('siswa_profiles.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user"></em></span>
                                <span class="nk-menu-text">Profil Siswa</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->check() && in_array(auth()->user()->role_id, [2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('kelas.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-home"></em></span>
                                <span class="nk-menu-text">Daftar Kelas</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && in_array(auth()->user()->role_id, [1 ,2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('ujian.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span>
                                <span class="nk-menu-text">Manajemen Ujian</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && in_array(auth()->user()->role_id, [1 ,2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('nilai.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-check-circle"></em></span>
                                <span class="nk-menu-text">Penilaian Ujian</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && in_array(auth()->user()->role_id, [1, 2]))
                    <li class="nk-menu-item">
                        <a href="{{ route('riwayat.point.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-coin"></em></span>
                            <span class="nk-menu-text">Riwayat points</span>
                        </a>
                    </li>
                    @endif

                    @if (auth()->check() && in_array(auth()->user()->role_id, [1, 2]))
                        <li class="nk-menu-item">
                            <a href="{{ route('laporan.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-file-text"></em></span>
                                <span class="nk-menu-text">Laporan</span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check())
                        <li class="nk-menu-item">
                            <a href="{{ route('logout') }}" class="nk-menu-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="nk-menu-icon"><em class="icon ni ni-signout"></em></span>
                                <span class="nk-menu-text">Log Out</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
