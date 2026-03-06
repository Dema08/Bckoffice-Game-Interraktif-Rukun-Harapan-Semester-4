@extends('admin.layout.main')

@section('content')
    <div class="container mt-4">
        <h2>Edit Ujian</h2>
        <form method="POST" action="{{ route('ujian.update', $ujian->id) }}" id="form-ujian" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control"
                    value="{{ old('judul', $ujian->judul) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="guru_id">Guru</label>
                <select name="guru_id" id="guru_id" class="form-control" required>
                    @foreach ($guruProfiles as $guru)
                        <option value="{{ $guru->id }}"
                            {{ old('guru_id', $ujian->guru_id) == $guru->id ? 'selected' : '' }}>
                            {{ $guru->full_name }} ({{ $guru->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control" required>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}"
                            {{ old('kelas_id', $ujian->kelas_id) == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} - {{ $kelas->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="mapel_id">Mata Pelajaran</label>
                <select name="mapel_id" id="mapel_id" class="form-control" required>
                    @foreach ($mapelList as $mapel)
                        <option value="{{ $mapel->id }}"
                            {{ old('mapel_id', $ujian->mapel_id) == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="waktu_mulai">Waktu Mulai</label>
                    <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control"
                        value="{{ old('waktu_mulai', \Carbon\Carbon::parse($ujian->waktu_mulai)->format('Y-m-d\TH:i')) }}"
                        required>
                </div>
                <div class="col">
                    <label for="waktu_selesai">Waktu Selesai</label>
                    <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="form-control"
                        value="{{ old('waktu_selesai', \Carbon\Carbon::parse($ujian->waktu_selesai)->format('Y-m-d\TH:i')) }}"
                        required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="tipe_ujian">Tipe Ujian</label>
                <select name="tipe_ujian" id="tipe_ujian" class="form-control">
                    <option value="magic_card"
                        {{ old('tipe_ujian', $ujian->tipe_ujian) == 'magic_card' ? 'selected' : '' }}>Magic Card</option>
                    <option value="choose_it" {{ old('tipe_ujian', $ujian->tipe_ujian) == 'choose_it' ? 'selected' : '' }}>
                        Choose It</option>
                </select>
            </div>

            <hr>
            <h5>Edit Soal</h5>
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
                    @foreach ($ujian->soal as $key => $s)
                        <tr>
                            <td>
                                <input type="hidden" name="soal[{{ $s->id }}][id]" value="{{ $s->id }}">
                                <input type="text" name="soal[{{ $s->id }}][pertanyaan]" class="form-control"
                                    value="{{ old("soal.$s->id.pertanyaan", $s->pertanyaan) }}" required>

                                <div class="mt-2">
                                    <input type="file" name="soal[{{ $s->id }}][gambar]" class="form-control"
                                        accept="image/*" onchange="previewImage(this)"
                                        {{ old("soal.$s->id.gambar") ? 'required' : '' }}>
                                    @if ($s->gambar)
                                        <img src="{{ asset('public/storage/' . $s->gambar) }}" alt="Gambar Soal"
                                            class="img-preview img-fluid mt-2" style="max-height: 200px; display: block;">
                                    @else
                                        <img src="" alt="Preview Gambar" class="img-preview img-fluid mt-2"
                                            style="display: none;">
                                    @endif
                                </div>
                            </td>
                            <td>
                                <select name="soal[{{ $s->id }}][tipe]" class="form-control tipe-soal">
                                    <option value="pilihan_ganda"
                                        {{ old("soal.$s->id.tipe", $s->tipe) === 'pilihan_ganda' ? 'selected' : '' }}>
                                        Pilihan Ganda</option>
                                    <option value="essay"
                                        {{ old("soal.$s->id.tipe", $s->tipe) === 'essay' ? 'selected' : '' }}>Essay
                                    </option>
                                </select>
                            </td>
                            <td>
                                <div class="jawaban-benar"
                                    style="display: {{ $s->tipe === 'pilihan_ganda' ? 'none' : '' }};">
                                    <input type="text" name="soal[{{ $s->id }}][jawaban_benar]"
                                        class="form-control jawaban-benar-input"
                                        value="{{ old("soal.$s->id.jawaban_benar", $s->jawaban_benar) }}"
                                        {{ $s->tipe === 'pilihan_ganda' ? 'readonly' : '' }}>
                                </div>
                                <div class="opsi-container">
                                    @if (old("soal.$s->id.tipe", $s->tipe) === 'pilihan_ganda')
                                        @php
                                            $opsiMap = [];
                                            if (!empty(old("soal.$s->id.opsi"))) {
                                                for ($i = 0; $i < 5; $i++) {
                                                    $label = chr(65 + $i);
                                                    $opsiMap[] = old("soal.$s->id.opsi.$label");
                                                }
                                            } else {
                                                foreach ($s->opsiJawaban as $opsiRow) {
                                                    $opsiMap[] = $opsiRow->opsi;
                                                }
                                                while (count($opsiMap) < 5) {
                                                    $opsiMap[] = '';
                                                }
                                            }

                                            $benar = old("soal.$s->id.jawaban_benar", $s->jawaban_benar);
                                        @endphp
                                        @for ($i = 0; $i < 5; $i++)
                                            @php
                                                $label = chr(65 + $i);
                                                $isiOpsi = $opsiMap[$i] ?? '';
                                            @endphp
                                            <div class="form-check">
                                                <input class="form-check-input jawaban-radio" type="radio"
                                                    name="soal[{{ $s->id }}][jawaban_benar]"
                                                    value="{{ $label }}" {{ $label === $benar ? 'checked' : '' }}
                                                    required>
                                                <label class="form-check-label">
                                                    {{ $label }}:
                                                    <input type="text"
                                                        name="soal[{{ $s->id }}][opsi][{{ $label }}]"
                                                        class="form-control d-inline-block w-auto"
                                                        value="{{ $isiOpsi }}" required>
                                                </label>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($ujian->tipe_ujian === 'magic_card')
                <a href="{{ route('ujian.export.magic_card', ['id' => $ujian->id]) }}" class="btn btn-outline-primary btn-sm">Export Magic Card PDF</a>
            @endif
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-tambah-soal">+ Tambah Soal Baru</button>
            <hr>
            <button type="submit" class="btn btn-primary">Perbarui Ujian</button>
            <a href="{{ route('ujian.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let soalIndex = {{ $ujian->soal->count() }};

        function addSoalRow() {
            const tbody = document.querySelector('#table-soal tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="text" name="soal[new_${soalIndex}][pertanyaan]" class="form-control" required>
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
            setupHapusHandler(row);
            soalIndex++;
        }

        function setupHapusHandler(row) {
            const btnHapus = row.querySelector('.btn-remove');
            btnHapus.addEventListener('click', () => {
                row.remove();
            });
        }

        document.getElementById('table-soal').addEventListener('click', (e) => {
            if (e.target.closest('.btn-remove')) {
                e.target.closest('tr').remove();
            }
        });

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

            tipeSelect.addEventListener('change', function() {
                if (this.value === 'pilihan_ganda') {
                    if (opsiContainer.children.length === 0) {
                        opsiContainer.innerHTML = generateOpsiInputs(index);
                    }
                    jawabanInput.closest('.jawaban-benar').style.display = 'none';
                } else {
                    opsiContainer.innerHTML = '';
                    jawabanInput.closest('.jawaban-benar').style.display = '';
                }
            });

            tipeSelect.dispatchEvent(new Event('change'));
        }

        document.querySelectorAll('#table-soal tbody tr').forEach(row => {
            setupOpsiHandler(row, row.querySelector('[name*="[pertanyaan]"]').closest('tr').querySelector('.tipe-soal').value);
            setupHapusHandler(row);
        });

        function previewImage(input) {
            const file = input.files[0];
            const imgPreview = input.parentElement.querySelector('.img-preview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
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
