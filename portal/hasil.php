<?php
session_start();
include '../inc/config.php';

$nisn = isset($_POST['nisn']) ? mysqli_real_escape_string($conn, $_POST['nisn']) : (isset($_GET['nisn']) ? mysqli_real_escape_string($conn, $_GET['nisn']) : null);

if (!$nisn) {
    header("Location: index.html");
    exit;
}

// Query database
$query = "SELECT * FROM data_kelulusan WHERE nisn = '$nisn' LIMIT 1";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    $notFound = true;
    $student = null;
} else {
    $notFound = false;
    $_SESSION['nisn'] = $nisn;
}

$isLulus = false;
if ($student) {
    $statusRaw = strtolower((string) ($student['status_lulus'] ?? '0'));
    $isLulus = $statusRaw === '1' || $statusRaw === 'lulus';
}

function indoTanggal($timestamp)
{
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $d = (int) date('j', $timestamp);
    $m = (int) date('n', $timestamp);
    $y = date('Y', $timestamp);
    return $d . ' ' . $bulan[$m] . ' ' . $y;
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kelulusan - SMKN 4 Bojonegoro</title>
    <meta name="description" content="Hasil pengumuman kelulusan SMKN 4 Bojonegoro">
    <link rel="stylesheet" href="./assets/theme.css" />
  </head>
  <body>
    <div class="min-h-screen bg-muted/40 py-10 px-4">
      <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6 print:hidden">
          <a
            href="index.html"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
            </svg>
            Kembali
          </a>

          <button
            onclick="window.print()"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z" />
            </svg>
            Cetak
          </button>
        </div>

        <div class="bg-card rounded-2xl shadow-elegant overflow-hidden border animate-scale-in">
          <div class="bg-gradient-hero text-primary-foreground p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-primary/20"></div>
            <div class="relative flex flex-col items-center gap-3">
              <img src="./assets/logo-smkn4.png" alt="Logo SMKN 4 Bojonegoro" class="h-20 w-auto drop-shadow-lg" />
              <div>
                <p class="text-xs uppercase tracking-widest opacity-90">Pemerintah Provinsi Jawa Timur</p>
                <h1 class="text-2xl md:text-3xl font-bold">SMK Negeri 4 Bojonegoro</h1>
                <p class="text-sm opacity-90 mt-1">Pengumuman Hasil Kelulusan TP 2025/2026</p>
              </div>
            </div>
          </div>

          <?php if ($notFound): ?>
            <div class="p-8 text-center text-success-foreground bg-destructive">
              <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-4 animate-fade-in-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="h-12 w-12">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.9 19.1a10 10 0 1 1 14.2 0 10 10 0 0 1-14.2 0Z" />
                </svg>
              </div>
              <p class="text-sm uppercase tracking-widest opacity-90 mb-1">Status Pencarian</p>
              <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight animate-fade-in-up">DATA TIDAK DITEMUKAN</h2>
              <p class="text-base opacity-95 mt-3 max-w-md mx-auto">
                NISN <?= htmlspecialchars($nisn); ?> tidak terdaftar. Periksa kembali NISN Anda atau hubungi panitia.
              </p>
            </div>
            <div class="p-8 md:p-10">
              <a
                href="index.html"
                class="inline-flex w-full md:w-auto items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
              >
                Kembali ke Pencarian
              </a>
            </div>
          <?php else: ?>
            <div class="p-8 text-center text-success-foreground <?= $isLulus ? 'bg-gradient-success' : 'bg-destructive'; ?>">
              <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm mb-4 animate-fade-in-up">
                <?php if ($isLulus): ?>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="h-12 w-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                <?php else: ?>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="h-12 w-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 9-6 6m0-6 6 6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                <?php endif; ?>
              </div>
              <p class="text-sm uppercase tracking-widest opacity-90 mb-1">Dengan Ini Dinyatakan</p>
              <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight animate-fade-in-up"><?= $isLulus ? 'LULUS' : 'TIDAK LULUS'; ?></h2>
              <?php if ($isLulus): ?>
                <p class="text-base opacity-95 mt-3 max-w-md mx-auto">Selamat! Anda telah berhasil menyelesaikan studi di SMK Negeri 4 Bojonegoro.</p>
              <?php endif; ?>
            </div>

            <div class="p-8 md:p-10 space-y-1">
              <h3 class="text-xs uppercase tracking-widest text-muted-foreground mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M22 10v6M2 10l10-5 10 5-10 5-10-5Zm4 2.5V16a6 3 0 0 0 12 0v-3.5" />
                </svg>
                Data Siswa
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3 border-b last:border-b-0 border-border/60">
                <p class="text-sm text-muted-foreground">Nama Lengkap</p>
                <p class="md:col-span-2 text-base text-foreground font-medium"><?= htmlspecialchars($student['nama']); ?></p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3 border-b last:border-b-0 border-border/60">
                <p class="text-sm text-muted-foreground">NISN</p>
                <p class="md:col-span-2 text-base text-foreground font-medium font-mono tracking-wider"><?= htmlspecialchars($student['nisn']); ?></p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3 border-b last:border-b-0 border-border/60">
                <p class="text-sm text-muted-foreground">Kelas</p>
                <p class="md:col-span-2 text-base text-foreground font-medium"><?= htmlspecialchars($student['kelas'] ?? '-'); ?></p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3 border-b last:border-b-0 border-border/60">
                <p class="text-sm text-muted-foreground">Kompetensi Keahlian</p>
                <p class="md:col-span-2 text-base text-foreground font-medium"><?= htmlspecialchars($student['jurusan']); ?></p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 py-3 border-b last:border-b-0 border-border/60">
                <p class="text-sm text-muted-foreground">Status Kelulusan</p>
                <p class="md:col-span-2 text-base text-foreground font-medium <?= $isLulus ? 'text-success font-bold' : 'text-destructive font-bold'; ?>">
                  <?= $isLulus ? 'LULUS' : 'TIDAK LULUS'; ?>
                </p>
              </div>
            </div>

            <div class="bg-muted/50 p-6 text-center text-xs text-muted-foreground border-t">
              Pengumuman ini bersifat resmi dan dikeluarkan oleh panitia kelulusan SMK Negeri 4 Bojonegoro.
              <br />
              Bojonegoro, <?= indoTanggal(time()); ?>
            </div>
          <?php endif; ?>
        </div>

        <p class="text-center text-xs text-muted-foreground mt-6 print:hidden">
          Simpan atau cetak halaman ini sebagai bukti pengumuman sementara.
        </p>
      </div>
    </div>
  </body>
</html>
