<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Services\SheetsSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    public function create()
    {
        return view('absen');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150'],
            'tanggal_absen' => ['required', 'date'],
            'jenis_absen' => ['required', Rule::in(['masuk', 'pulang'])],
            'status_peserta' => ['required', Rule::in(Absensi::STATUS)],
            'nama' => ['required', 'string', 'max:150'],
            'asal_instansi' => ['required', 'string', 'max:200'],
            'divisi' => ['required', Rule::in(array_keys(Absensi::DIVISI))],
            'foto' => ['required', 'string', 'max:3000000'],
        ], [
            'foto.required' => 'Foto wajah wajib diambil.',
        ]);

        // Cegah absen ganda (email + tanggal + jenis)
        $sudah = Absensi::where('email', $data['email'])
            ->whereDate('tanggal_absen', $data['tanggal_absen'])
            ->where('jenis_absen', $data['jenis_absen'])
            ->exists();

        if ($sudah) {
            return back()->withInput($request->except('foto'))
                ->withErrors(['jenis_absen' => 'Kamu sudah absen ' . $data['jenis_absen'] . ' pada tanggal tersebut.']);
        }

        // Decode foto base64 dari kamera (hanya JPEG)
        if (!preg_match('/^data:image\/jpeg;base64,/', $data['foto'])) {
            return back()->withInput($request->except('foto'))
                ->withErrors(['foto' => 'Format foto tidak valid, ambil ulang.']);
        }
        $base64 = substr($data['foto'], strpos($data['foto'], ',') + 1);
        $binary = base64_decode($base64, true);
        if ($binary === false) {
            return back()->withInput($request->except('foto'))
                ->withErrors(['foto' => 'Foto rusak, ambil ulang.']);
        }

        $path = 'absensi/' . now()->format('Ymd_His') . '_' . Str::random(8) . '.jpg';
        Storage::disk('public')->put($path, $binary);

        $absensi = Absensi::create([
            'email' => $data['email'],
            'tanggal_absen' => $data['tanggal_absen'],
            'jenis_absen' => $data['jenis_absen'],
            'status_peserta' => $data['status_peserta'],
            'nama' => $data['nama'],
            'asal_instansi' => $data['asal_instansi'],
            'divisi' => $data['divisi'],
            'foto' => $path,
        ]);

        // Kirim ke Google Sheet SETELAH respons terkirim, supaya form tidak lambat
        dispatch(function () use ($absensi, $base64) {
            SheetsSync::push($absensi, $base64);
        })->afterResponse();

        return redirect()->route('absen.create')
            ->with('sukses', 'Absen ' . $data['jenis_absen'] . ' berhasil dikirim. Terima kasih, ' . $data['nama'] . '!');
    }
}