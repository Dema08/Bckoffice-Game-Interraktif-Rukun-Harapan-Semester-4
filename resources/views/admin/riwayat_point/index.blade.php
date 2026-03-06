@extends('admin.layout.main')
@section('title', 'Riwayat Point Siswa')
@section('content')
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Riwayat Point Siswa</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Data point siswa berdasarkan penilaian otomatis.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-bordered">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <table class="datatable-init table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Siswa</th>
                                            <th>NIS</th>
                                            <th>Total Point</th>
                                            <th>Level</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPoints as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->full_name ?? '-' }}</td>
                                                <td>{{ $item->nis ?? '-' }}</td>
                                                <td>{{ $item->total_point ?? 0 }}</td>
                                                <td>{!! $item->levelLabel !!}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->last_date)->format('d M Y H:i') }}</td>
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
