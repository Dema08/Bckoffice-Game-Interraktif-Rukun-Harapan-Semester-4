@extends('admin.layout.main')

@section('title', 'Tambah Kelas')

@section('content')
<div class="nk-block-head">
    <div class="nk-block-head-content">
        <h4 class="nk-block-title">Tambah Kelas</h4>
    </div>
</div>
<div class="section-body">
    <form action="{{ route('kelas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Semester</label>
            <select name="semester" class="form-control" required>
                <option value="">Pilih Semester</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
