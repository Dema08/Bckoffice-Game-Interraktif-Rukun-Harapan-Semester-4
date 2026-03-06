@extends('admin.layout.main')

@section('title', 'Edit Profil Siswa')

@section('content')
<div class="nk-block-head">
  <div class="nk-block-head-content">
    <h4 class="nk-block-title">Edit Profil Siswa</h4>
  </div>
</div>

<div class="nk-block">
  <div class="card card-bordered">
    <div class="card-inner">
      <form action="{{ route('siswa_profiles.update', $siswa_profile->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text"
                 name="full_name"
                 class="form-control @error('full_name') is-invalid @enderror"
                 value="{{ old('full_name', $siswa_profile->full_name) }}"
                 required>
          @error('full_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label>NIS</label>
          <input type="text"
                 name="nis"
                 class="form-control @error('nis') is-invalid @enderror"
                 value="{{ old('nis', $siswa_profile->nis) }}"
                 required>
          @error('nis')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label>Username</label>
          <input type="text"
                 class="form-control"
                 value="{{ $siswa_profile->user->username }}"
                 readonly>
        </div>

        <div class="form-group">
          <label>Kelas</label>
          <select name="kelas_id"
                  class="form-control @error('kelas_id') is-invalid @enderror"
                  required>
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelas as $item)
              <option value="{{ $item->id }}"
                {{ (($siswa_profile->kelasSiswa->kelas_id ?? 0) == $item->id) ? 'selected' : '' }}>
                {{ $item->nama_kelas }}
              </option>
            @endforeach
          </select>
          @error('kelas_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-success">Update</button>
      </form>
    </div>
  </div>
</div>
@endsection
