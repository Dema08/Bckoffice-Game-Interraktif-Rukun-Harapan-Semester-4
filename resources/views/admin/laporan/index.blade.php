@extends('admin.layout.main')
@section('title', 'Laporan Nilai Ujian')
@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <h3 class="nk-block-title">Daftar Laporan Ujian</h3>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card card-bordered">
                    <div class="card-inner">
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
                                        <a href="{{ route('laporan.detail', $ujian->id) }}" class="btn btn-sm btn-info me-1">Detail</a>
                                        <form action="{{ route('laporan.export', $ujian->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Export Excel</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="nk-block-head mt-5">
                    <h5 class="nk-block-title">Riwayat Export Terbaru</h5>
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama File</th>
                                        <th>Tanggal Export</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (App\Models\ExportLog::where('user_id', auth()->id())->latest()->get() as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ basename($log->file_path) }}</td>
                                        <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <a href="{{ asset('public/storage/' . $log->file_path) }}" target="_blank" class="btn btn-sm btn-primary">Unduh File</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada riwayat export.</td>
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
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
@endsection
