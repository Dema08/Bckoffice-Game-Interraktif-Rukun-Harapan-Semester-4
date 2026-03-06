@extends('admin.layout.main')

@section('title', 'Daftar Kelas')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Daftar Kelas</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Daftar kelas yang terdaftar di sistem.</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('kelas.create') }}" class="btn btn-primary">
                                    <em class="icon ni ni-plus"></em><span>Tambah Kelas</span>
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
                                                <th>Nama Kelas</th>
                                                <th>Tahun Ajaran</th>
                                                <th>Semester</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kelas as $index => $k)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $k->nama_kelas }}</td>
                                                <td>{{ $k->tahun_ajaran }}</td>
                                                <td>{{ $k->semester == 1 ? 'Ganjil' : 'Genap' }}</td>
                                                <td>
                                                    <a href="{{ route('kelas.edit', $k->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="d-inline delete-form">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin_assets/js/libs/datatable-btns.js?ver=3.2.3') }}"></script><script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
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
