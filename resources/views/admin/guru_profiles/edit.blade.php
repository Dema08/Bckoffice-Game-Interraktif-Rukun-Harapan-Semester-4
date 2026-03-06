@extends('admin.layout.main')

@section('title', 'Edit Profil Guru')

@section('content')
<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">Edit Profil Guru</h4>
    </div>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('guru_profiles.update', $guruProfile->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="user_id">Pilih User</label>
                    <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('user_id', $guruProfile->user_id) == $user->id) ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $guruProfile->full_name) }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $guruProfile->nip) }}" required>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_wali">Apakah Wali Kelas?</label>
                    <select name="is_wali" id="is_wali" class="form-control @error('is_wali') is-invalid @enderror" required>
                        <option value="1" {{ old('is_wali', $guruProfile->is_wali) == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ old('is_wali', $guruProfile->is_wali) == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('is_wali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" id="kelasSelect">
                    <label for="kelas_id">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-control select2 @error('kelas_id') is-invalid @enderror">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ (($guruProfile->kelasGuru->kelas_id ?? 0) == $kelas->id) ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                                {{ $kelas->has_wali ? '(Sudah ada wali)' : '(Belum ada wali)' }}
                                {{ $kelas->has_wali ? '(Sudah ada wali)' : '(Belum ada wali)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('guru_profiles.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Cari dan pilih",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
