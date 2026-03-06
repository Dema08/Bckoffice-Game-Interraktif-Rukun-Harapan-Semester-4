@extends('admin.layout.main')

@section('title', 'Mata Pelajaran')

@section('styles')
@endsection
@push('style')

@endpush

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Mata Pelajaran</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Total Mata Pelajaran: {{ $mapel->count() }}</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
                                    <em class="icon ni ni-plus"></em><span>Tambah Mata Pelajaran</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card card-bordered">
                        <div class="card-inner-group">
                            <div class="card-inner ">
                                <div class="le-responsivtabe">
                                    <table class="datatable-init table" id="tableMapel">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Mapel</th>
                                                <th>Kode Mapel</th>
                                                <th>Deskripsi</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mapel as $m)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar sq bg-purple me-2">
                                                            <span>
                                                                @php
                                                                    $words = explode(' ', $m->nama_mapel);
                                                                    $initials = count($words) > 1
                                                                        ? strtoupper(substr($words[0], 0, 1)) . strtoupper(substr($words[1], 0, 1))
                                                                        : strtoupper(substr($words[0], 0, 2));
                                                                    echo $initials;
                                                                @endphp
                                                            </span>
                                                        </div>
                                                        <span>{{ $m->nama_mapel }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $m->kode_mapel }}</td>
                                                <td>{{ $m->deskripsi }}</td>
                                                <td class="text-end">
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $m->id }}">
                                                            <em class="icon ni ni-edit"></em> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $m->id }}">
                                                            <em class="icon ni ni-trash"></em> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('admin.mata_pelajaran.create')
                    @include('admin.mata_pelajaran.edit')
                    @include('admin.mata_pelajaran.delete')

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admin_assets/js/libs/datatable-btns.js?ver=3.2.3') }}"></script><script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
@push('style')

@endpush
@endsection
