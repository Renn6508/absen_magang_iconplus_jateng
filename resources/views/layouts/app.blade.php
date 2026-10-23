<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Absensi Magang') - PLN Icon Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; }
        .brand-bar { background: #0b5ea8; color: #fff; }
        .btn-pln { background: #0b5ea8; color: #fff; }
        .btn-pln:hover { background: #094a86; color: #fff; }
    </style>
</head>
<body>
    <div class="brand-bar py-3 mb-4">
        <div class="container">
            <div class="fw-bold">PLN Icon Plus SBU Regional Jawa Tengah</div>
            <small>Sistem Absensi Peserta Magang</small>
        </div>
    </div>
    <div class="container pb-5">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>