@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Rekap Absensi Peserta Magang</h5>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf
        <button class="btn btn-sm btn-outline-danger">Logout</button>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-6"><div class="card"><div class="card-body py-3">
        <div class="text-muted small">Absen masuk hari ini</div><div class="fs-4 fw-bold">{{ $masukHariIni }}</div>
    </div></div></div>
    <div class="col-6"><div class="card"><div class="card-body py-3">
        <div class="text-muted small">Absen pulang hari ini</div><div class="fs-4 fw-bold">{{ $pulangHariIni }}</div>
    </div></div></div>
</div>

<form method="GET" class="card card-body mb-3">
    <div class="row g-2">
        <div class="col-md-2"><input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}"></div>
        <div class="col-md-3">
            <select name="divisi" class="form-select">
                <option value="">Semua divisi</option>
                @foreach (array_keys(\App\Models\Absensi::DIVISI) as $d)
                    <option value="{{ $d }}" @selected(request('divisi') === $d)>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="jenis" class="form-select">
                <option value="">Masuk & pulang</option>
                <option value="masuk" @selected(request('jenis') === 'masuk')>Masuk</option>
                <option value="pulang" @selected(request('jenis') === 'pulang')>Pulang</option>
            </select>
        </div>
        <div class="col-md-3"><input type="text" name="cari" class="form-control" placeholder="Cari nama/email/asal" value="{{ request('cari') }}"></div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-pln flex-fill">Filter</button>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="card">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th><th>Foto</th><th>Nama</th><th>Asal</th><th>Divisi</th>
            <th>Tgl Absen</th><th>Jenis</th><th>Jam Kirim</th><th>Sheet</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($data as $i => $r)
        <tr>
            <td>{{ $data->firstItem() + $i }}</td>
            <td>
                <a href="{{ asset('storage/'.$r->foto) }}" target="_blank">
                    <img src="{{ asset('storage/'.$r->foto) }}" width="56" height="56" class="rounded object-fit-cover" alt="foto">
                </a>
            </td>
            <td>
                <div class="fw-semibold">{{ $r->nama }}</div>
                <div class="small text-muted">{{ $r->status_peserta }} · {{ $r->email }}</div>
            </td>
            <td>{{ $r->asal_instansi }}</td>
            <td class="small">{{ $r->divisi }}</td>
            <td>{{ $r->tanggal_absen->format('d/m/Y') }}</td>
            <td>
                <span class="badge {{ $r->jenis_absen === 'masuk' ? 'text-bg-success' : 'text-bg-warning' }}">
                    {{ strtoupper($r->jenis_absen) }}
                </span>
            </td>
            <td>{{ $r->created_at->format('d/m/Y H:i:s') }}</td>
            <td>{!! $r->synced_sheet ? '<span class="text-success">✔</span>' : '<span class="text-muted">-</span>' !!}</td>
        </tr>
    @empty
        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data absensi.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="card-footer">{{ $data->links('pagination::bootstrap-5') }}</div>
</div>
@endsection