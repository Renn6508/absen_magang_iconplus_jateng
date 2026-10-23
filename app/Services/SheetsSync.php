<?php

namespace App\Services;

use App\Models\Absensi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SheetsSync
{
    public static function push(Absensi $a, string $fotoBase64): void
    {
        $url = config('services.gsheet.url');
        if (!$url) {
            return;
        }

        try {
            $res = Http::timeout(30)->asJson()->post($url, [
                'token' => config('services.gsheet.token'),
                'waktu_kirim' => $a->created_at->format('d/m/Y H:i:s'),
                'tanggal_absen' => $a->tanggal_absen->format('d/m/Y'),
                'jenis_absen' => strtoupper($a->jenis_absen),
                'email' => $a->email,
                'status_peserta' => $a->status_peserta,
                'nama' => $a->nama,
                'asal_instansi' => $a->asal_instansi,
                'divisi' => $a->divisi,
                'filename' => basename($a->foto),
                'foto_base64' => $fotoBase64,
            ]);

            if ($res->successful() && $res->json('ok') === true) {
                $a->update(['synced_sheet' => true]);
            } else {
                Log::warning('Sync Google Sheet gagal', ['body' => $res->body()]);
            }
        } catch (\Throwable $e) {
            Log::warning('Sync Google Sheet error: ' . $e->getMessage());
        }
    }
}