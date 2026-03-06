<?php

namespace App\Http\Controllers;

use App\Models\SiswaProfile;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\SiswaProfileService;

class SiswaProfileController extends Controller
{
    protected $siswaService;

    public function __construct(SiswaProfileService $siswaService)
    {
        $this->siswaService = $siswaService;
    }
    public function index(Request $request)
    {
        $siswaProfiles = $this->siswaService->getAllProfiles();
        $kelas = $this->siswaService->getAllKelas();

        return view('admin.siswa_profiles.index', compact('siswaProfiles', 'kelas'));
    }

    public function create()
    {
        $users = $this->siswaService->getAvailableUsers();
        $kelas = $this->siswaService->getAllKelas();

        return view('admin.siswa_profiles.create', compact('users', 'kelas'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->siswaService->validateStore($request->all());
            $this->siswaService->createSiswaProfile($validated);
            return redirect()->route('siswa_profiles.index')->with('success', 'Profil siswa berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $siswa_profile = SiswaProfile::with(['user', 'kelasSiswa','kelasSiswa.kelas','kelasSiswa.siswa'])->findOrFail($id);

        $kelas = $this->siswaService->getAllKelas();

        $selectedKelasId = $siswa_profile->kelas->first()?->id;

        return view('admin.siswa_profiles.edit', compact('siswa_profile', 'kelas', 'selectedKelasId'));
    }

    public function update(Request $request, SiswaProfile $siswa_profile)
    {
        $validated = $this->siswaService->validateUpdate($request->all(), $siswa_profile);
        $this->siswaService->updateSiswaProfile($siswa_profile, $validated);
        return redirect()->route('siswa_profiles.index')->with('success', 'Profil siswa berhasil diperbarui.');
    }

    public function destroy(SiswaProfile $siswa_profile)
    {
        try {
            $this->siswaService->deleteSiswaProfile($siswa_profile);
            return redirect()->route('siswa_profiles.index')->with('success', 'Profil siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
