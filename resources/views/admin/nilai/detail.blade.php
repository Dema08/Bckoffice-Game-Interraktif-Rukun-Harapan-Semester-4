@extends('admin.layout.main')
@section('title', 'Detail Jawaban Siswa')
@section('content')
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <h3 class="nk-block-title">Jawaban - {{ $siswas->full_name }}</h3>
                    </div>
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <form action="{{ route('nilai.simpan.manual') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
                                <input type="hidden" name="siswa_id" value="{{ $siswa->user_id }}">

                                <table class="table table-hover align-middle">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban Siswa</th>
                                            <th>Jawaban Benar</th>
                                            <th>Tipe Soal</th>
                                            <th>Status Otomatis</th>
                                            <th>Koreksi Manual</th>
                                        </tr>
                                    </thead>
                                    <tbody id="soal-body">
                                        @foreach ($jawaban as $j)
                                            <tr>
                                                <td>{!! $j->soal->pertanyaan !!}</td>
                                                <td>{{ $j->jawaban }}</td>
                                                <td>{{ $j->soal->getQrText() }}</td>

                                                <td>
                                                    @if ($j->soal->tipe === 'pilihan_ganda')
                                                        <span class="badge bg-primary">PG</span>
                                                    @else
                                                        <span class="badge bg-info">Esai</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($j->soal->tipe === 'pilihan_ganda')
                                                        @if (strtolower(trim($j->jawaban)) == strtolower(trim($j->soal->getQrText())))
                                                            <span class="badge bg-success" data-status="benar">Benar</span>
                                                        @else
                                                            <span class="badge bg-danger" data-status="salah">Salah</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($j->soal->tipe === 'essay')
                                                        <select name="koreksi[{{ $j->soal_id }}]"
                                                            class="form-select form-select-sm koreksi-select">
                                                            <option value="">-- Pilih --</option>
                                                            <option value="benar">Benar</option>
                                                            <option value="salah">Salah</option>
                                                        </select>
                                                    @else
                                                        <input type="hidden" name="koreksi[{{ $j->soal_id }}]"
                                                            class="koreksi-hidden"
                                                            value="{{ strtolower(trim($j->jawaban)) == strtolower(trim($j->soal->getQrText())) ? 'benar' : 'salah' }}">
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label for="nilai" class="form-label">Nilai Akhir</label>
                                        <input type="number" name="nilai" id="nilai" class="form-control"
                                            step="0.01" min="0" max="100" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="feedback" class="form-label">Feedback</label>
                                        <textarea name="feedback" id="feedback" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="{{ route('nilai.siswa', $ujian->id) }}" class="btn btn-secondary"><em
                                            class="icon ni ni-arrow-left"></em> Kembali</a>
                                    <button type="submit" class="btn btn-success"><em class="icon ni ni-save"></em> Simpan
                                        Nilai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>
    <script src="{{ asset('admin_assets/js/libs/datatable.js?ver=3.2.3') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selects = document.querySelectorAll('.koreksi-select');
            const hiddenInputs = document.querySelectorAll('.koreksi-hidden');
            const nilaiInput = document.getElementById('nilai');

            function hitungNilai() {
                let jumlahSoal = 0;
                let jumlahBenar = 0;

                selects.forEach(select => {
                    if (select.value === 'benar') {
                        jumlahBenar++;
                    }
                    if (select.value !== '') {
                        jumlahSoal++;
                    }
                });

                hiddenInputs.forEach(input => {
                    if (input.value === 'benar') {
                        jumlahBenar++;
                    }
                    jumlahSoal++;
                });

                if (jumlahSoal > 0) {
                    const nilaiAkhir = (jumlahBenar / jumlahSoal) * 100;
                    nilaiInput.value = nilaiAkhir.toFixed(2);
                } else {
                    nilaiInput.value = '';
                }
            }

            selects.forEach(select => {
                select.addEventListener('change', hitungNilai);
            });

            hitungNilai();
        });
    </script>
@endsection
