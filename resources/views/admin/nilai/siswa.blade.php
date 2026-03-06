@extends('admin.layout.main')
@section('title', 'Daftar Siswa')
@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <h3 class="nk-block-title">Siswa - {{ $ujian->judul }}</h3>
                </div>
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="le-responsivtabe">
                            <table class="datatable-init table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Nilai</th>
                                        <th>Feedback</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswas as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->full_name }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>
                                            @php
                                                $nilaiUjian = optional($siswa->nilai->firstWhere('ujian_id', $ujian->id))->nilai ?? '-';
                                            @endphp
                                            {{ $nilaiUjian }}
                                        </td>
                                        <td>
                                            {{ optional($siswa->nilai->firstWhere('ujian_id', $ujian->id))->feedback ?? '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $status = optional($siswa->nilai->firstWhere('ujian_id', $ujian->id))->status ?? 'not_graded';
                                            @endphp
                                            @if($status === 'graded')
                                                <span class="badge bg-success">Sudah Dinilai</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('nilai.detail', [$ujian->id, $siswa->user_id]) }}" class="btn btn-sm btn-info">Detail Jawaban</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('nilai.index') }}" class="btn btn-secondary"><em class="icon ni ni-arrow-left"></em> Kembali</a>
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
