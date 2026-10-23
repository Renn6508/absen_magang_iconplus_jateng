<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'email', 'tanggal_absen', 'jenis_absen', 'status_peserta',
        'nama', 'asal_instansi', 'divisi', 'foto', 'synced_sheet',
    ];

    protected $casts = [
        'tanggal_absen' => 'date',
        'synced_sheet' => 'boolean',
    ];

    public const STATUS = ['Siswa', 'Siswi', 'Mahasiswa', 'Mahasiswi'];

    public const DIVISI = [
        'OPERASI, PEMELIHARAAN DAN ASET JATENG' => 'Aset, CA, Inventory, NOC',
        'PEMASARAN DAN PENJUALAN B2B JATENG' => 'Cusol, Admin Sales, Collection',
        'PENJUALAN RITEL DAN ADMINISTRASI JATENG' => 'Sales Retail, Collection Retail, Keuangan, Humas',
        'PEMBANGUNAN DAN DELIVERY LAYANAN JATENG' => 'Pembangunan, Aktivasi Publik, Aktivasi Retail',
    ];
}