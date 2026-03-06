@extends('admin.layout.main')

@section('title', 'Daftar Ujian')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Daftar Ujian</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Daftar ujian yang terdaftar di sistem.</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ url('/ujian/create') }}" class="btn btn-primary">
                                    <em class="icon ni ni-plus"></em><span>Tambah Ujian</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card card-bordered">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <div class="le-responsivtabe">
                                    <table class="datatable-init table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Judul</th>
                                                <th>Guru</th>
                                                <th>Kelas</th>
                                                <th>Mapel</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ujians as $index => $ujian)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $ujian->judul }}</td>
                                                <td>{{ $ujian->guru->full_name ?? '-' }}</td>
                                                <td>{{ $ujian->kelas->nama_kelas ?? '-' }}</td>
                                                <td>{{ $ujian->mapel->nama_mapel ?? '-' }}</td>
                                                <td>
                                                    @if($ujian->waktu_mulai)
                                                        {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('Y-m-d H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url("/ujian/{$ujian->id}/edit") }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ url("/ujian/{$ujian->id}") }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                                                    </form>
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

                <div class="nk-block-footer">
                    {{ $ujians->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin_assets/js/libs/datatable-btns.js?ver=3.2.3') }}"></script>
<script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
