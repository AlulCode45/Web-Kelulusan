# 📚 Portal Pengumuman Kelulusan SMKN 4 Bojonegoro

Integrasi aplikasi modern **e-graduation-smkn4** (Frontend React) dengan **Web-Kelulusan** (Backend PHP) menjadi satu portal pengumuman kelulusan yang elegan dan responsif.

## 🎯 Fitur Utama

### 🌐 Frontend Modern
- **Portal Pengumuman**: Interface yang indah dan responsif untuk mencari hasil kelulusan
- **Desain Modern**: Gradient, animasi, dan UX yang baik
- **Mobile Friendly**: Sempurna untuk semua ukuran perangkat
- **Cetak Hasil**: Fitur print-friendly untuk hasil kelulusan

### 👨‍💼 Admin Dashboard
- **Manajemen Data Siswa**: Tambah, lihat, dan hapus data siswa
- **Autentikasi Aman**: Login system untuk admin
- **Antarmuka Modern**: Dashboard yang user-friendly
- **Validasi Data**: Validasi input yang ketat

### 💾 Database Integration
- **MySQL Database**: Integrasi penuh dengan database existing
- **Data Persistent**: Semua data tersimpan aman di database
- **Query Optimized**: Query yang efisien untuk performa maksimal

## 📁 Struktur Folder

```
Web-Kelulusan/
├── portal/                  # Portal pencarian kelulusan (BARU)
│   ├── index.html          # Halaman pencarian modern
│   └── hasil.php           # Halaman hasil kelulusan
├── portal-landing.html     # Landing page dengan menu pilihan
├── admin/
│   ├── login.php           # Login admin
│   ├── index-new.php       # Dashboard admin modern (BARU)
│   ├── tambahSiswa.php     # Process tambah siswa
│   └── hapusData.php       # Process hapus siswa
├── assets/                 # Gambar dan aset
├── css/
│   └── styles.css          # Stylesheet
├── inc/
│   └── config.php          # Database configuration
└── index.php               # Main entry point

e-graduation-smkn-4/        # Frontend React (original)
├── dist/                   # Build output (static files)
├── src/                    # Source code React
└── package.json
```

## 🚀 Cara Instalasi

### 1. Setup Database

```sql
# Import database
mysql -u root -p smkn4bojonegoro_kelulusan < smkn4bojonegoro_kelulusan.sql
```

### 2. Konfigurasi Database

Edit file `inc/config.php`:

```php
<?php
$host = "127.0.0.1";
$user = "root";              // Sesuaikan dengan user MySQL Anda
$pass = "password_anda";     // Sesuaikan dengan password MySQL Anda
$data = "smkn4bojonegoro_kelulusan";
$conn = mysqli_connect($host, $user, $pass, $data);
?>
```

### 3. Setup Web Server

```bash
# Gunakan XAMPP/WAMP atau server lokal
# Copy folder ke htdocs (XAMPP) atau www (WAMP)

# Atau jalankan built-in PHP server
cd Web-Kelulusan
php -S localhost:8000
```

### 4. Akses Portal

- **Portal Utama**: `http://localhost:8000/portal-landing.html`
- **Cek Kelulusan**: `http://localhost:8000/portal/index.html`
- **Admin Dashboard**: `http://localhost:8000/admin/login.php`

## 💻 Cara Penggunaan

### Untuk Siswa/Publik

1. Buka `http://localhost:8000/portal-landing.html`
2. Klik tombol "Cek Kelulusan"
3. Masukkan NISN (10 digit angka)
4. Tekan tombol "Cari Hasil"
5. Lihat hasil kelulusan Anda
6. Opsional: Klik "Cetak" untuk mencetak hasil

### Untuk Admin

1. Buka `http://localhost:8000/admin/login.php`
2. Login dengan username dan password admin
3. Kelola data siswa:
   - **Tambah**: Isi form di atas tabel dan klik "Simpan Data"
   - **Lihat**: Tabel akan menampilkan semua data siswa
   - **Hapus**: Klik tombol "Hapus" di kolom Aksi

## 📝 Panduan Pengembang

### Build Frontend React

```bash
cd e-graduation-smkn-4
bun install        # Install dependencies
bun run build      # Build untuk production
```

Output akan berada di folder `dist/`

### Struktur Database

**Tabel: data_kelulusan**

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | ID unik (auto increment) |
| nisn | VARCHAR(255) | Nomor Induk Siswa Nasional |
| nama | VARCHAR(255) | Nama lengkap siswa |
| jurusan | VARCHAR(255) | Kompetensi keahlian |
| status_lulus | TINYINT | 1=Lulus, 0=Tidak Lulus |

**Tabel: users**

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | BIGINT | ID unik |
| name | VARCHAR(255) | Nama admin |
| username | VARCHAR(255) | Username login |
| password | VARCHAR(255) | Password (di-hash) |

### Menambah Data Siswa via Script

```php
<?php
include 'inc/config.php';

$nisn = "0051234567";
$nama = "Ahmad Fauzan Hidayat";
$jurusan = "Rekayasa Perangkat Lunak 1";
$status_lulus = 1; // 1=Lulus, 0=Tidak Lulus

$query = "INSERT INTO data_kelulusan (nisn, nama, jurusan, status_lulus) 
          VALUES ('$nisn', '$nama', '$jurusan', $status_lulus)";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "Data berhasil ditambahkan";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
```

## 🎨 Teknologi yang Digunakan

### Frontend (React - e-graduation-smkn4)
- **React 18** - UI Library
- **TypeScript** - Type safety
- **Vite** - Build tool
- **Tailwind CSS** - Styling
- **shadcn/ui** - Component library
- **React Router** - Navigation

### Frontend (Portal - Baru)
- **HTML5** - Markup
- **CSS3** - Styling dengan CSS Grid & Flexbox
- **Vanilla JavaScript** - Interaktif tanpa library

### Backend
- **PHP 7.4+** - Server-side
- **MySQL** - Database
- **MySQLi** - Database extension

### Tools
- **Bun** - Package manager & runtime
- **Node.js** - Development environment

## 🔒 Keamanan

### Tips Keamanan

1. **Database Credentials**: Jangan commit file `config.php` dengan credentials asli
   ```bash
   echo "inc/config.php" >> .gitignore
   ```

2. **Password Hashing**: Selalu hash password admin
   ```php
   $hashed = password_hash($password, PASSWORD_BCRYPT);
   ```

3. **SQL Injection Prevention**: Gunakan prepared statements
   ```php
   $stmt = $conn->prepare("SELECT * FROM data_kelulusan WHERE nisn = ?");
   $stmt->bind_param("s", $nisn);
   $stmt->execute();
   ```

4. **HTTPS**: Gunakan HTTPS di production
5. **Rate Limiting**: Tambahkan rate limiting untuk API

## 📊 API Reference

### Portal Public

#### Halaman Pencarian
- **URL**: `/portal/index.html`
- **Method**: GET
- **Description**: Tampilkan form pencarian NISN

#### Hasil Kelulusan
- **URL**: `/portal/hasil.php`
- **Method**: POST
- **Parameters**:
  - `nisn` (required): Nomor Induk Siswa Nasional
- **Response**: HTML page dengan hasil kelulusan atau error

### Admin

#### Login
- **URL**: `/admin/login.php`
- **Method**: POST
- **Parameters**:
  - `username` (required)
  - `password` (required)

#### Dashboard
- **URL**: `/admin/index.php` atau `/admin/index-new.php`
- **Method**: GET
- **Auth**: Memerlukan session login

#### Tambah Siswa
- **URL**: `/admin/tambahSiswa.php`
- **Method**: POST
- **Parameters**:
  - `nisn` (required)
  - `nama` (required)
  - `jurusan` (required)
  - `status_lulus` (required)

## 🐛 Troubleshooting

### Error: "Database connection failed"
```
Solusi:
1. Pastikan MySQL sudah running
2. Cek kredensial di inc/config.php
3. Pastikan database sudah di-import
```

### Error: "NISN tidak ditemukan"
```
Solusi:
1. Pastikan NISN sudah ditambahkan di admin panel
2. Check NISN format (10 digit)
3. Verifikasi data di tabel data_kelulusan
```

### Error: "Login gagal"
```
Solusi:
1. Pastikan session sudah diaktifkan
2. Check session.save_path di php.ini
3. Clear browser cookies dan coba lagi
```

## 📞 Support

Untuk bantuan lebih lanjut:

1. **Chat Admin**: Hubungi admin SMK Negeri 4 Bojonegoro
2. **Email**: smkn4bojonegoro@example.com
3. **Website**: https://smkn4bojonegoro.sch.id

## 📄 Lisensi

Proyek ini adalah milik SMK Negeri 4 Bojonegoro. Penggunaan hanya untuk keperluan sekolah.

## 🎓 Informasi Sekolah

**SMK Negeri 4 Bojonegoro**
- Alamat: Jl. Soekarno-Hatta No. 125, Bojonegoro, Jawa Timur
- Email: smkn4bjn@yahoo.com
- Website: smkn4bojonegoro.sch.id

---

**Versi**: 2.0 (Integrated)  
**Terakhir Diupdate**: 1 Mei 2026  
**Status**: Production Ready ✅
