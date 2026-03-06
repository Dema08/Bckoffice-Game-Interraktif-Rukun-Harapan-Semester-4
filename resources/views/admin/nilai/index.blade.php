@extends('admin.layout.main')
@section('title', 'Penilaian Ujian')
@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <h3 class="nk-block-title">Daftar Nilai Ujian</h3>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="le-responsivtabe">
                            <table class="datatable-init table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Judul Ujian</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ujians as $index => $ujian)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ujian->judul }}</td>
                                        <td>{{ $ujian->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $ujian->mapel->nama_mapel ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('nilai.siswa', $ujian->id) }}" class="btn btn-sm btn-primary">Lihat Siswa</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
