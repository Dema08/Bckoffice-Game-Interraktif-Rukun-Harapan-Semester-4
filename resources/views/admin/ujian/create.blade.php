@extends('admin.layout.main')

@section('title', 'Tambah Ujian')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title">Tambah Ujian</h4>
                            <div class="nk-block-des">
                                <p>Form untuk menambahkan ujian baru ke sistem.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-bordered">
                        <div class="card-inner">
                            <form method="POST" action="{{ route('ujian.store') }}" id="form-ujian" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label class="form-label" for="judul">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="guru_id">Guru</label>
                                    <select class="form-control" name="guru_id" id="guru_id" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($guruProfiles as $guru)
                                            <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                                {{ $guru->full_name }} ({{ $guru->nip }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="kelas_id">Kelas</label>
                                    <select class="form-control" name="kelas_id" id="kelas_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }} - {{ $kelas->tahun_ajaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="mapel_id">Mata Pelajaran</label>
                                    <select class="form-control" name="mapel_id" id="mapel_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach ($mapelList as $mapel)
                                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                                {{ $mapel->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="waktu_mulai">Waktu Mulai</label>
                                            <input type="datetime-local" class="form-control" name="waktu_mulai" id="waktu_mulai"
                                                   value="{{ old('waktu_mulai', now()->format('Y-m-d\TH:i')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="waktu_selesai">Waktu Selesai</label>
                                            <input type="datetime-local" class="form-control" name="waktu_selesai" id="waktu_selesai"
                                                   value="{{ old('waktu_selesai', now()->addHour()->format('Y-m-d\TH:i')) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="tipe_ujian">Tipe Ujian</label>
                                    <select name="tipe_ujian" id="tipe_ujian" class="form-control" required>
                                        <option value="magic_card" {{ old('tipe_ujian') == 'magic_card' ? 'selected' : '' }}>Magic Card</option>
                                        <option value="choose_it" {{ old('tipe_ujian') == 'choose_it' ? 'selected' : '' }}>Choose It</option>
                                    </select>
                                </div>

                                <hr>
                                <h5>Soal</h5>
                                <table class="table table-bordered" id="table-soal">
                                    <thead>
                                        <tr>
                                            <th>Pertanyaan</th>
                                            <th>Tipe</th>
                                            <th>Jawaban Benar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-tambah-soal">+ Tambah Soal Baru</button>

                                <hr>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Simpan Ujian</button>
                                    <a href="{{ route('ujian.index') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let soalIndex = 1;

    function addSoalRow() {
        const tbody = document.querySelector('#table-soal tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="text" name="soal[new_${soalIndex}][pertanyaan]" class="form-control" required>
                <!-- Upload Gambar -->
                <div class="mt-2">
                    <input type="file" name="soal[new_${soalIndex}][gambar]" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <img src="" alt="Preview Gambar" class="img-preview img-fluid mt-2" style="max-height: 200px; display: none;">
                </div>
            </td>
            <td>
                <select name="soal[new_${soalIndex}][tipe]" class="form-control tipe-soal">
                    <option value="pilihan_ganda">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                </select>
            </td>
            <td>
                <div class="jawaban-benar" style="display: none;">
                    <input type="text" name="soal[new_${soalIndex}][jawaban_benar]" class="form-control jawaban-benar-input">
                </div>
                <div class="opsi-container"></div>
            </td>
            <td><button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button></td>
        `;
        tbody.appendChild(row);
        setupOpsiHandler(row, `new_${soalIndex}`);
        soalIndex++;
    }

    document.getElementById('btn-tambah-soal').addEventListener('click', addSoalRow);

    function generateOpsiInputs(index) {
        let html = '';
        for (let i = 0; i < 5; i++) {
            const label = String.fromCharCode(65 + i);
            html += `
                <div class="form-check">
                    <input class="form-check-input jawaban-radio" type="radio"
                           name="soal[${index}][jawaban_benar]" value="${label}" required>
                    <label class="form-check-label">
                        ${label}:
                        <input type="text" name="soal[${index}][opsi][${label}]"
                               class="form-control d-inline-block w-auto" required>
                    </label>
                </div>`;
        }
        return html;
    }

    function setupOpsiHandler(row, index) {
        const tipeSelect = row.querySelector('.tipe-soal');
        const opsiContainer = row.querySelector('.opsi-container');
        const jawabanInput = row.querySelector('.jawaban-benar-input');

        tipeSelect.addEventListener('change', function () {
            if (this.value === 'pilihan_ganda') {
                opsiContainer.innerHTML = generateOpsiInputs(index);
                jawabanInput.closest('.jawaban-benar').style.display = 'none';
            } else {
                opsiContainer.innerHTML = '';
                jawabanInput.closest('.jawaban-benar').style.display = '';
            }
        });

        tipeSelect.dispatchEvent(new Event('change'));
    }

    document.querySelectorAll('tr').forEach(tr => {
        const tipeSelect = tr.querySelector('.tipe-soal');
        const opsiContainer = tr.querySelector('.opsi-container');
        if (tipeSelect && opsiContainer) {
            setupOpsiHandler(tr, tr.querySelector('[name*="[pertanyaan]"]').closest('tr').querySelector('.tipe-soal').value);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove')) {
            e.target.closest('tr').remove();
        }
    });

    function previewImage(input) {
        const file = input.files[0];
        const imgPreview = input.parentElement.querySelector('.img-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imgPreview.src = '';
            imgPreview.style.display = 'none';
        }
    }
</script>
@endpush
