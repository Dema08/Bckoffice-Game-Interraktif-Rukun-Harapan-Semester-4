<?php

namespace App\Services;

use App\Models\KelasSiswa;
use App\Models\SiswaProfile;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class SiswaProfileService
{
    public function getAllProfiles()
    {
        $user = Auth::user();

        // Muat relasi user, kelasSiswa, dan kelas yang terkait
        $query = SiswaProfile::with(['user', 'kelasSiswa.kelas']);

        if ($user->role_id == 1) {
            $kelasIds = DB::table('kelas_guru')->where('guru_id', $user->id)->pluck('kelas_id');

            // Sesuaikan dengan relasi: siswaProfile -> kelasSiswa -> kelas
            $query->whereHas('kelasSiswa.kelas', function ($q) use ($kelasIds) {
                $q->whereIn('id', $kelasIds);
            });

        }

        return $query->get();
    }

    public function getAllKelas()
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            // hanya kelas guru
            return DB::table('kelas_guru')->where('guru_id', $user->id)->join('kelas', 'kelas.id', '=', 'kelas_guru.kelas_id')->select('kelas.*')->get();
        }
        return Kelas::all();
    }

    public function getAvailableUsers()
    {
        $user = Auth::user();

        $query = User::where('role_id', 3)->whereDoesntHave('siswaProfile');

        // Hapus blok if guru supaya tidak filter berdasarkan kelas
        // karena siswa baru belum masuk kelas mana pun.

        return $query->get();
    }

    public function validateStore(array $data)
    {
        $validator = Validator::make(
            $data,
            [
                'user_id' => ['required', 'unique:siswa_profiles,user_id', Rule::exists('users', 'id')->where(fn($q) => $q->where('role_id', 3))],
                'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'nis' => ['required', 'numeric', 'unique:siswa_profiles,nis'],
                'kelas_id' => 'required|exists:kelas,id',
            ],
            [
                'full_name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
                'nis.numeric' => 'NIS hanya boleh berisi angka.',
            ],
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function validateUpdate(array $data, SiswaProfile $siswaProfile)
    {
        $validator = Validator::make(
            $data,
            [
                'full_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'nis' => ['required', 'numeric', Rule::unique('siswa_profiles', 'nis')->ignore($siswaProfile->id)],
                'kelas_id' => 'required|exists:kelas,id',
            ],
            [
                'full_name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
                'nis.numeric' => 'NIS hanya boleh berisi angka.',
            ],
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function createSiswaProfile(array $data)
    {
        DB::beginTransaction();
        try {
            $siswaProfile = SiswaProfile::create([
                'user_id' => $data['user_id'],
                'full_name' => $data['full_name'],
                'nis' => $data['nis'],
            ]);

            $kelas = KelasSiswa::create([
                'siswa_id' => $siswaProfile->user_id,
                'kelas_id' => $data['kelas_id'],
            ]);

            DB::commit();
            return $siswaProfile;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan profil siswa: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateSiswaProfile(SiswaProfile $siswaProfile, array $validated)
    {
        \DB::beginTransaction();
        try {
            $siswaProfile->update([
                'full_name' => $validated['full_name'],
                'nis' => $validated['nis'],
            ]);
            
            $kelasSiswa = KelasSiswa::where('siswa_id', $siswaProfile->user_id)->first();
            if ($kelasSiswa) {
                $kelasSiswa->update([
                    'kelas_id' => $validated['kelas_id'],
                ]);
            } else {
                KelasSiswa::create([
                    'siswa_id' => $siswaProfile->user_id,
                    'kelas_id' => $validated['kelas_id'],
                ]);
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function deleteSiswaProfile(SiswaProfile $siswaProfile)
    {
        DB::beginTransaction();
        try {
            $siswaProfile->kelas()->detach();
            $siswaProfile->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus profil siswa: ' . $e->getMessage());
            throw $e;
        }
    }

    public function syncTotalPointToProfiles()
    {
        $points = DB::table('riwayat_point')->select('id_siswa', DB::raw('SUM(jumlah_point) as total'))->groupBy('id_siswa')->get();

        foreach ($points as $data) {
            SiswaProfile::where('user_id', $data->id_siswa)->update(['point' => $data->total]);
        }
    }
}
