@extends('admin.layout.main')

@section('title', 'Tambah Profil Guru')

@section('content')
<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">Tambah Profil Guru</h4>
    </div>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('guru_profiles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user_id">Pilih User</label>
                    <select id="user_id" name="user_id" class="form-control select2" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" id="nip" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_wali">Apakah menjadi wali kelas?</label>
                    <select id="is_wali" name="is_wali" class="form-control" required>
                        <option value="0" {{ old('is_wali') == '0' ? 'selected' : '' }}>Tidak</option>
                        <option value="1" {{ old('is_wali') == '1' ? 'selected' : '' }}>Ya</option>
                    </select>
                    @error('is_wali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kelas_id">Pilih Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="form-control select2" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}"
                                data-has-wali="{{ $k->has_wali ? '1' : '0' }}"
                                {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} {{ $k->has_wali ? '(sudah ada wali)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('guru_profiles.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
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

        $('#kelas_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const hasWali = selectedOption.data('has-wali');
            const isWali = $('#is_wali').val();

            if (isWali == '1' && hasWali == '1') {
                alert('Kelas ini sudah memiliki wali kelas. Silakan pilih kelas lain.');
                $(this).val('').trigger('change');
            }
        });

        $('#is_wali').on('change', function() {
            $('#kelas_id').trigger('change'); 
        });
    });
</script>
@endsection
