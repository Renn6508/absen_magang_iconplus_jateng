@extends('layouts.app')
@section('title', 'Form Absen')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

@if (session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form id="formAbsen" method="POST" action="{{ route('absen.store') }}" class="card shadow-sm">
@csrf
<div class="card-body p-4">

    {{-- ===== HALAMAN 1 ===== --}}
    <div id="step1" @if($errors->any()) class="d-none" @endif>
        <h5 class="mb-3">Peraturan Magang</h5>
        <div class="border rounded p-3 bg-light mb-3 small">
            <strong>Aturan izin saat magang:</strong>
            <ol class="mt-2 mb-3">
                <li>Izin ke pembimbing divisi dan ke koordinator magang, diusahakan izin H-1 atau paling telat pagi hari, sertakan alasan izin.</li>
                <li>Izin karena sakit yang lebih dari 2 hari harus menunjukkan surat keterangan dokter.</li>
                <li>Hari libur menyesuaikan pemberitahuan dari kantor, jangan ada yang meliburkan diri.</li>
                <li>Ada atau tidak pekerjaan yang didisposisi oleh pembimbing magang harus tetap masuk kantor, silakan inisiatif untuk membantu karyawan lain yang ada di divisi penempatan masing-masing.</li>
            </ol>
            <div><strong>Hari kerja:</strong> Senin - Jumat</div>
            <div><strong>Jam kerja:</strong> Senin - Kamis 08.00 - 17.00, Jumat 07.30 - 17.00</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Absen</label>
            <input type="date" name="tanggal_absen" id="tanggal" class="form-control"
                   value="{{ old('tanggal_absen', now()->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Jenis Absen</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_absen" id="jm" value="masuk" @checked(old('jenis_absen') === 'masuk')>
                <label class="form-check-label" for="jm">Absen Masuk</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_absen" id="jp" value="pulang" @checked(old('jenis_absen') === 'pulang')>
                <label class="form-check-label" for="jp">Absen Pulang</label>
            </div>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="setuju">
            <label class="form-check-label" for="setuju">Saya sudah membaca dan menyetujui peraturan magang.</label>
        </div>
        <button type="button" id="btnLanjut" class="btn btn-pln w-100">Lanjut</button>
    </div>

    {{-- ===== HALAMAN 2 ===== --}}
    <div id="step2" @if(!$errors->any()) class="d-none" @endif>
        <h5 class="mb-3">Data Diri & Foto</h5>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status_peserta" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach (\App\Models\Absensi::STATUS as $s)
                    <option value="{{ $s }}" @selected(old('status_peserta') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Asal Sekolah / Universitas</label>
            <input type="text" name="asal_instansi" class="form-control" value="{{ old('asal_instansi') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Divisi Penempatan Magang</label>
            <select name="divisi" class="form-select" required>
                <option value="">-- Pilih divisi --</option>
                @foreach (\App\Models\Absensi::DIVISI as $nama => $sub)
                    <option value="{{ $nama }}" @selected(old('divisi') === $nama)>{{ $nama }} ({{ $sub }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Wajah (saat absen)</label>
            <div class="border rounded p-2 text-center bg-light">
                <video id="video" class="w-100 rounded d-none" autoplay playsinline muted></video>
                <canvas id="canvas" class="d-none"></canvas>
                <img id="preview" class="w-100 rounded d-none" alt="Preview foto">
                <div id="camInfo" class="text-muted small py-4">Kamera belum aktif</div>
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" id="btnKamera" class="btn btn-outline-secondary flex-fill">Buka Kamera</button>
                <button type="button" id="btnAmbil" class="btn btn-outline-primary flex-fill d-none">Ambil Foto</button>
                <button type="button" id="btnUlang" class="btn btn-outline-danger flex-fill d-none">Ulangi</button>
            </div>
            <input type="hidden" name="foto" id="foto">
        </div>

        <div class="d-flex gap-2">
            <button type="button" id="btnKembali" class="btn btn-outline-secondary">Kembali</button>
            <button type="submit" id="btnKirim" class="btn btn-pln flex-fill">Kirim Absen</button>
        </div>
    </div>

</div>
</form>

<div class="text-center mt-3"><a href="{{ route('login') }}" class="small text-muted">Login Admin</a></div>
</div></div>
@endsection

@push('scripts')
<script>
const $ = id => document.getElementById(id);
const step1 = $('step1'), step2 = $('step2');
let stream = null;

// ---- Navigasi halaman ----
$('btnLanjut').onclick = () => {
    const email = $('email').value.trim();
    if (!email || !$('email').checkValidity()) return alert('Isi email dengan benar.');
    if (!$('tanggal').value) return alert('Pilih tanggal absen.');
    if (!document.querySelector('input[name=jenis_absen]:checked')) return alert('Pilih absen masuk atau pulang.');
    if (!$('setuju').checked) return alert('Centang persetujuan peraturan magang.');
    step1.classList.add('d-none');
    step2.classList.remove('d-none');
    window.scrollTo(0, 0);
};
$('btnKembali').onclick = () => {
    stopCam();
    step2.classList.add('d-none');
    step1.classList.remove('d-none');
};

// ---- Kamera ----
async function startCam() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        $('video').srcObject = stream;
        $('video').classList.remove('d-none');
        $('preview').classList.add('d-none');
        $('camInfo').classList.add('d-none');
        $('btnKamera').classList.add('d-none');
        $('btnAmbil').classList.remove('d-none');
        $('btnUlang').classList.add('d-none');
    } catch (e) {
        alert('Kamera tidak bisa diakses. Izinkan akses kamera di browser (butuh HTTPS atau localhost).');
    }
}
function stopCam() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
}
$('btnKamera').onclick = startCam;
$('btnUlang').onclick = () => { $('foto').value = ''; startCam(); };

$('btnAmbil').onclick = () => {
    const v = $('video'), c = $('canvas');
    const w = 480, h = Math.round(w * (v.videoHeight / v.videoWidth));
    c.width = w; c.height = h;
    c.getContext('2d').drawImage(v, 0, 0, w, h);
    const data = c.toDataURL('image/jpeg', 0.7);
    $('foto').value = data;
    $('preview').src = data;
    $('preview').classList.remove('d-none');
    v.classList.add('d-none');
    stopCam();
    $('btnAmbil').classList.add('d-none');
    $('btnUlang').classList.remove('d-none');
};

// ---- Kirim ----
$('formAbsen').addEventListener('submit', e => {
    if (!$('foto').value) { e.preventDefault(); return alert('Ambil foto wajah dulu.'); }
    $('btnKirim').disabled = true;
    $('btnKirim').textContent = 'Mengirim...';
});
</script>
@endpush