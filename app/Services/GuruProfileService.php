<?php

namespace App\Services;

use App\Models\GuruProfile;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GuruProfileService
{
    public function getAllProfiles()
    {
        return GuruProfile::with('user')->get();
    }

    public function getAvailableUsers()
    {
        return User::where('role_id', 1)
            ->whereDoesntHave('guruProfile')
            ->get();
    }

    public function getAllKelas()
    {
        return Kelas::all();
    }

    public function validateStore(Request $request): array
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'full_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-Z\s]+$/'
        ], 
        'nip' => [
            'required',
            'numeric',
            'unique:guru_profiles,nip'
        ], 
        'is_wali' => 'required|boolean',
        'kelas_id' => ['nullable', 'exists:kelas,id'],
    ], [
        'full_name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        'nip.numeric' => 'NIP hanya boleh berisi angka.',
    ]);

    $data = $validator->validate();

    if ($data['is_wali'] && empty($data['kelas_id'])) {
        throw ValidationException::withMessages([
            'kelas_id' => ['Kelas wajib diisi jika menjadi wali kelas.'],
        ]);
    }

    return $data;
}

public function validateUpdate(Request $request, GuruProfile $guruProfile): array
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'full_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-Z\s]+$/'
        ], 
        'nip' => [
            'required',
            'numeric',
            'unique:guru_profiles,nip,' . $guruProfile->id
        ], 
        'is_wali' => 'required|in:0,1',
        'kelas_id' => ['nullable', 'exists:kelas,id'],
    ], [
        'full_name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
        'nip.numeric' => 'NIP hanya boleh berisi angka.',
    ]);

    $data = $validator->validate();

    if ($data['is_wali'] && empty($data['kelas_id'])) {
        throw ValidationException::withMessages([
            'kelas_id' => ['Kelas wajib diisi jika menjadi wali kelas.'],
        ]);
    }

    return $data;
}

    public function createGuruProfile(array $data)
    {
        DB::beginTransaction();
        try {
            $guruProfile = GuruProfile::create([
                'user_id' => $data['user_id'],
                'full_name' => $data['full_name'],
                'nip' => $data['nip'],
            ]);

            if (isset($data['kelas_id'])) {
                if ($data['is_wali']) {
                    $kelasSudahAdaWali = DB::table('kelas_guru')
                        ->where('kelas_id', $data['kelas_id'])
                        ->where('is_wali', 1)
                        ->exists();

                    if ($kelasSudahAdaWali) {
                        throw new \Exception('Kelas ini sudah memiliki wali kelas.');
                    }
                }

                DB::table('kelas_guru')->insert([
                    'guru_id' => $data['user_id'],
                    'kelas_id' => $data['kelas_id'],
                    'is_wali' => $data['is_wali'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return $guruProfile;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan profil guru: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateGuruProfile(GuruProfile $guruProfile, array $data)
    {
        DB::beginTransaction();
        try {
            $guruProfile->update([
                'user_id' => $data['user_id'],
                'full_name' => $data['full_name'],
                'nip' => $data['nip'],
            ]);

            DB::table('kelas_guru')->where('guru_id', $guruProfile->user_id)->delete();

            if (isset($data['kelas_id'])) {
                if ($data['is_wali']) {
                    $kelasSudahAdaWali = DB::table('kelas_guru')
                        ->where('kelas_id', $data['kelas_id'])
                        ->where('is_wali', 1)
                        ->exists();

                    if ($kelasSudahAdaWali) {
                        throw new \Exception('Kelas ini sudah memiliki wali kelas.');
                    }
                }

                DB::table('kelas_guru')->insert([
                    'guru_id' => $data['user_id'],
                    'kelas_id' => $data['kelas_id'],
                    'is_wali' => $data['is_wali'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update profil guru: ' . $e->getMessage());
            throw $e;
        }
    }


    public function deleteGuruProfile(GuruProfile $guruProfile)
    {
        DB::beginTransaction();
        try {
            DB::table('kelas_guru')->where('guru_id', $guruProfile->user_id)->delete();
            $guruProfile->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus profil guru: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getWaliKelas($userId)
    {
        return DB::table('kelas_guru')
            ->where('guru_id', $userId)
            ->where('is_wali', 1)
            ->first();
    }
}
