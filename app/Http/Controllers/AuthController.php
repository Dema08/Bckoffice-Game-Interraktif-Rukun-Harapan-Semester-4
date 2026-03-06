<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $result = $this->authService->attemptLogin($request->only('username', 'password'));

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }
          $userroleid = Auth::user()->role_id;
        if ($userroleid == 3) {
            return redirect()->route('login')->with('error', 'Anda Tidak Memiliki Hak Akses Ini.');
        }

        $request->session()->put('jwt_token', $result['token']);
        $request->session()->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil login!');
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout();

        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with(
            $result['success'] ? 'success' : 'error',
            $result['success'] ? 'Anda berhasil logout.' : $result['message']
        );
    }
}
