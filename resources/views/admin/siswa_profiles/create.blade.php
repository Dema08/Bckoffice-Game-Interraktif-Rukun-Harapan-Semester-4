@extends('admin.layout.main')

@section('content')
<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">Tambah Profil Siswa</h4>
    </div>
</div>

<div class="nk-block">
    <div class="card card-bordered">
        <div class="card-inner">
            <form action="{{ route('siswa_profiles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">Pilih Pengguna (Username)</label>
                    <select name="user_id" id="user_id" class="form-control select2" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="nis">NIS</label>
                    <input type="text" name="nis" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="kelas_id">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-control select2" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Cari dan pilih",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
