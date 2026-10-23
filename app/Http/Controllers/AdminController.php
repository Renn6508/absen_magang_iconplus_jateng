<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($cred)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function index(Request $request)
    {
        $q = Absensi::query()->latest();

        if ($request->filled('tanggal')) {
            $q->whereDate('tanggal_absen', $request->tanggal);
        }
        if ($request->filled('divisi')) {
            $q->where('divisi', $request->divisi);
        }
        if ($request->filled('jenis')) {
            $q->where('jenis_absen', $request->jenis);
        }
        if ($request->filled('cari')) {
            $c = $request->cari;
            $q->where(fn ($x) => $x->where('nama', 'like', "%$c%")
                ->orWhere('email', 'like', "%$c%")
                ->orWhere('asal_instansi', 'like', "%$c%"));
        }

        return view('admin.index', [
            'data' => $q->paginate(20)->withQueryString(),
            'masukHariIni' => Absensi::whereDate('tanggal_absen', today())->where('jenis_absen', 'masuk')->count(),
            'pulangHariIni' => Absensi::whereDate('tanggal_absen', today())->where('jenis_absen', 'pulang')->count(),
        ]);
    }
}