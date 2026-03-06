<?php

namespace App\Http\Controllers;

use App\Services\GuruProfileService;
use App\Models\GuruProfile;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;

class GuruProfileController extends Controller
{
    protected $guruProfileService;

    public function __construct(GuruProfileService $guruProfileService)
    {
        $this->guruProfileService = $guruProfileService;
    }

    public function index()
    {
        $guruProfiles = $this->guruProfileService->getAllProfiles();
        $users = $this->guruProfileService->getAvailableUsers();

        return view('admin.guru_profiles.index', compact('guruProfiles', 'users'));
    }

    public function create()
    {
        $users = $this->guruProfileService->getAvailableUsers();

        // ambil semua kelas + tandai kalau sudah punya wali
        $kelas = Kelas::with('waliGuru')->get()->map(function ($item) {
            $item->has_wali = $item->waliGuru ? true : false;
            return $item;
        });

        // ambil semua kelas + tandai kalau sudah punya wali
        $kelas = Kelas::with('waliGuru')->get()->map(function ($item) {
            $item->has_wali = $item->waliGuru ? true : false;
            return $item;
        });

        return view('admin.guru_profiles.create', compact('users', 'kelas'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->guruProfileService->validateStore($request);
            $this->guruProfileService->createGuruProfile($validated);
            return redirect()->route('guru_profiles.index')->with('success', 'Profil guru berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $guruProfile = GuruProfile::with(['user', 'kelasGuru'])->findOrFail($id);

        // Cek apakah guru profile memiliki user_id yang valid
        if (!$guruProfile->user_id) {
            return redirect()->route('guru_profiles.index')->with('error', 'Profil guru tidak ditemukan.');
        }
    
        $users = User::where('role_id', 1)
            ->where(function ($query) use ($guruProfile) {
                $query->whereDoesntHave('guruProfile')->orWhere('id', $guruProfile->user_id);
            })
            ->get();

        
        $kelasList = Kelas::with('waliGuru')->get()->map(function ($item) {
            $item->has_wali = $item->waliGuru ? true : false;
            return $item;
        });

        
        $kelasList = Kelas::with('waliGuru')->get()->map(function ($item) {
            $item->has_wali = $item->waliGuru ? true : false;
            return $item;
        });

        $waliKelas = $this->guruProfileService->getWaliKelas($guruProfile->user_id);



        return view('admin.guru_profiles.edit', compact('guruProfile', 'users', 'kelasList', 'waliKelas'));
    }



    public function update(Request $request, GuruProfile $guruProfile)
    {
        try {
            $validated = $this->guruProfileService->validateUpdate($request, $guruProfile);
            $this->guruProfileService->updateGuruProfile($guruProfile, $validated);
            return redirect()->route('guru_profiles.index')->with('success', 'Profil guru berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(GuruProfile $guruProfile)
    {
        try {
            $this->guruProfileService->deleteGuruProfile($guruProfile);
            return redirect()->route('guru_profiles.index')->with('success', 'Profil guru berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
