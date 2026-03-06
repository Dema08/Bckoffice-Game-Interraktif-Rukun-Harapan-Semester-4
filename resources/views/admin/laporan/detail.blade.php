@extends('admin.layout.main')
@section('title', 'Detail Laporan Nilai')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <h3 class="nk-block-title">Laporan Nilai - {{ $ujian->judul }}</h3>
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
                                    <th>Nama Siswa</th>
                                    <th>NIS</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nama_siswa }}</td>
                                        <td>{{ $item->nis }}</td>
                                        <td>{{ $item->nilai }}</td>
                                        <td>{{ ucfirst($item->status) }}</td>
                                        <td>{{ $item->feedback }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary mt-3">
                            <em class="icon ni ni-arrow-left"></em> Kembali
                        </a>
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
