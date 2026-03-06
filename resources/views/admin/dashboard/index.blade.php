@extends('admin.layout.main')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Overview</h4>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Guru</h5>
                        <h3 class="mb-0">{{ $totalGuru }}</h3>
                    </div>
                    <i class="bx bx-chalkboard bx-lg text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Total Siswa</h5>
                        <h3 class="mb-0">{{ $totalSiswa }}</h3>
                    </div>
                    <i class="bx bx-user-check bx-lg text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Kelas Aktif</h5>
                        <h3 class="mb-0">{{ $kelasAktif }}</h3>
                    </div>
                    <i class="bx bx-building bx-lg text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    Daftar Guru
                    <a href="{{ route('guru_profiles.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guruProfiles as $guru)
                                <tr>
                                    <td>{{ $guru->full_name }}</td>
                                    <td>{{ $guru->nip }}</td>
                                    <td>
                                        @php
                                            $isWali = $guru->user->kelasGuru->contains('is_wali', 1);
                                        @endphp
                                        @if ($isWali)
                                            <span class="badge bg-success">Wali Kelas</span>
                                        @else
                                            <span class="badge bg-secondary">Guru</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada guru ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    Ranking Siswa
                    <a href="{{ route('riwayat.point.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama</th>
                                <th>Level</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rankedStudents as $siswa)
                                <tr>
                                    <td>
                                        @if ($loop->iteration == 1)
                                            🥇
                                        @elseif ($loop->iteration == 2)
                                            🥈
                                        @elseif ($loop->iteration == 3)
                                            🥉
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </td>
                                    <td>{{ $siswa->full_name }}</td>
                                    <td>{!! $siswa->levelLabel !!}</td>
                                    <td>{{ $siswa->total_point }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada siswa yang memiliki point.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mb-4">
            <div class="card card-bordered card-full h-100">
                <div class="card-inner">
                    <div class="card-title-group mb-3 d-flex justify-content-between align-items-center">
                        <h5 class="title">Daftar Siswa</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="datatable-init table table-bordered table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIS</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaProfiles as $siswa)
                                    <tr>
                                        <td>{{ $siswa->full_name }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ optional($siswa->kelasSiswa?->kelas)->nama_kelas ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
@endsection
