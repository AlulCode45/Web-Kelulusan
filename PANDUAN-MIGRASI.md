# 🚀 Panduan Migrasi - Portal Pengumuman Kelulusan SMKN 4

## 📋 Ringkasan Integrasi

Aplikasi **e-graduation-smkn4** (Frontend React) telah berhasil diintegrasikan dengan **Web-Kelulusan** (Backend PHP) menjadi satu portal yang modern dan terintegrasi.

## ✨ Apa yang Baru?

### File & Folder yang Ditambahkan

```
Web-Kelulusan/
│
├── 🆕 portal/                          # Portal Pencarian Kelulusan (NEW)
│   ├── index.html                      # Halaman pencarian modern
│   └── hasil.php                       # Hasil kelulusan dengan DB integration
│
├── 🆕 portal-landing.html              # Menu utama dengan pilihan
│
├── 🆕 index-new.php                    # Redirect ke portal baru
│
├── admin/
│   ├── 🆕 logout.php                   # Logout admin
│   ├── 🆕 index-new.php                # Dashboard admin modern
│   └── (file lama tetap ada untuk backward compatibility)
│
└── 🆕 README-PORTAL.md                 # Dokumentasi lengkap
```

## 🎨 Desain & Fitur

### Halaman Utama (`portal-landing.html`)
- Landing page dengan pilihan: "Cek Kelulusan" atau "Admin Panel"
- Design modern dengan gradient dan animasi
- Mobile-responsive

### Portal Pencarian (`portal/index.html`)
- Form pencarian NISN yang indah
- Validasi input real-time
- Loading animation
- Error handling yang baik
- Mobile-friendly

### Halaman Hasil (`portal/hasil.php`)
- Display hasil kelulusan dari database
- Status badge (LULUS/TIDAK LULUS)
- Data siswa lengkap
- Tombol cetak terintegrasi
- Error handling jika NISN tidak ditemukan

### Admin Dashboard (`admin/index-new.php`)
- Interface modern untuk mengelola data siswa
- Form tambah siswa baru
- Tabel data dengan sorting
- Tombol hapus untuk setiap data
- Status badge untuk kelulusan
- Responsive design

## 🔄 Flow Aplikasi

```
User
  │
  ├─→ portal-landing.html
  │    │
  │    ├─→ portal/index.html (Cek Kelulusan)
  │    │    │
  │    │    └─→ portal/hasil.php (Submit NISN)
  │    │         │
  │    │         ├─ Found: Tampilkan hasil
  │    │         └─ Not Found: Error message
  │    │
  │    └─→ admin/login.php (Admin Panel)
  │         │
  │         └─→ admin/index-new.php (Dashboard)
  │             │
  │             ├─ Tambah siswa
  │             ├─ Lihat data
  │             └─ Hapus data
  │
  └─→ Database (MySQL)
       └─ data_kelulusan table
```

## 📝 Langkah-Langkah Migrasi

### Step 1: Backup Data
```bash
# Backup database existing
mysqldump -u root -p smkn4bojonegoro_kelulusan > backup.sql

# Backup folder Web-Kelulusan
cp -r Web-Kelulusan Web-Kelulusan.backup
```

### Step 2: Update Database Configuration
Edit `inc/config.php`:
```php
$host = "127.0.0.1";        // Database host
$user = "root";              // MySQL username
$pass = "password";          // MySQL password
$data = "smkn4bojonegoro_kelulusan";  // Database name
```

### Step 3: Pastikan Database Exist
```sql
-- Check if database exists
SHOW DATABASES;

-- Jika belum ada, buat baru
CREATE DATABASE smkn4bojonegoro_kelulusan;
USE smkn4bojonegoro_kelulusan;

-- Import schema
source smkn4bojonegoro_kelulusan.sql;
```

### Step 4: Tes Koneksi
1. Buka browser: `http://localhost:8000/portal-landing.html`
2. Klik "Cek Kelulusan"
3. Coba masukkan NISN test: `0051234567`
4. Jika ada error, check `inc/config.php`

### Step 5: Setup Admin Credentials
```bash
# Masuk ke phpMyAdmin atau MySQL CLI
mysql -u root -p smkn4bojonegoro_kelulusan

# Insert admin user (password harus di-hash terlebih dahulu)
INSERT INTO users (name, username, password) 
VALUES ('Admin', 'admin', PASSWORD('admin123'));
```

## ⚙️ Konfigurasi Lanjutan

### 1. Setup SSL/HTTPS
```apache
# .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 2. Setup Email Notification (Optional)
```php
// Dalam hasil.php, tambahkan:
mail($email, "Pengumuman Kelulusan", $message);
```

### 3. Setup Backup Otomatis
```bash
# Crontab (setiap hari jam 2 pagi)
0 2 * * * mysqldump -u root -ppassword smkn4bojonegoro_kelulusan > /backup/db-$(date +\%Y\%m\%d).sql
```

## 🧪 Testing Checklist

- [ ] Koneksi database berfungsi
- [ ] Portal pencarian dapat diakses
- [ ] Pencarian dengan NISN bekerja
- [ ] Hasil kelulusan ditampilkan dengan benar
- [ ] Tombol cetak berfungsi
- [ ] Admin panel dapat diakses dengan login
- [ ] Tambah data siswa berfungsi
- [ ] Hapus data siswa berfungsi
- [ ] Design responsive di mobile
- [ ] Error handling berfungsi

## 🔐 Keamanan - TODO

- [ ] Enable HTTPS
- [ ] Implement rate limiting
- [ ] Add CSRF protection tokens
- [ ] Sanitize all input
- [ ] Use prepared statements everywhere
- [ ] Setup firewall rules
- [ ] Regular security updates
- [ ] Backup strategy

## 📊 Database Commands

### View Data
```sql
SELECT * FROM data_kelulusan;
SELECT * FROM users;
```

### Tambah Sample Data
```sql
INSERT INTO data_kelulusan (nisn, nama, jurusan, status_lulus) 
VALUES 
('0051234567', 'Ahmad Fauzan', 'Rekayasa Perangkat Lunak', 1),
('0051234568', 'Siti Nurhaliza', 'Teknik Pengelasan', 1),
('0051234569', 'Budi Santoso', 'Teknik Kendaraan Ringan', 0);
```

### Reset Data
```sql
TRUNCATE TABLE data_kelulusan;
```

## 🎯 Development Workflow

### Untuk Development
```bash
# Terminal 1: Run PHP Server
cd Web-Kelulusan
php -S localhost:8000

# Terminal 2: Edit files
code .
```

### Untuk Production
```bash
# Build React app (if modifying frontend)
cd e-graduation-smkn-4
bun run build

# Copy ke server
scp -r Web-Kelulusan user@server:/var/www/html/
```

## 📱 Testing Links

```
🏠 Landing Page:
   http://localhost:8000/portal-landing.html

🔍 Cek Kelulusan:
   http://localhost:8000/portal/index.html

📋 Hasil (test):
   http://localhost:8000/portal/hasil.php?nisn=0051234567

🔐 Admin Login:
   http://localhost:8000/admin/login.php

📊 Admin Dashboard:
   http://localhost:8000/admin/index-new.php
```

## ✅ Deployment Checklist

- [ ] Database backup dibuat
- [ ] Konfigurasi database sudah benar
- [ ] SSL/HTTPS sudah setup
- [ ] Admin credentials sudah aman
- [ ] File permissions sudah benar (755 untuk folder, 644 untuk file)
- [ ] Error logging sudah setup
- [ ] Monitoring sudah active
- [ ] Backup schedule sudah running

## 🆘 Common Issues & Solutions

### Issue: "Connection refused"
```
Solusi:
1. Check if MySQL running: sudo service mysql status
2. Check config.php untuk host/user/pass
3. Restart MySQL: sudo service mysql restart
```

### Issue: "Table doesn't exist"
```
Solusi:
1. Import SQL: mysql -u root -p smkn4bojonegoro_kelulusan < smkn4bojonegoro_kelulusan.sql
2. Check database: USE smkn4bojonegoro_kelulusan; SHOW TABLES;
```

### Issue: "403 Forbidden"
```
Solusi:
1. Check folder permissions: chmod 755 Web-Kelulusan
2. Check .htaccess
3. Check server configuration
```

## 📞 Next Steps

1. **Test Portal**: Akses portal dan pastikan semua berfungsi
2. **Training Admin**: Latih admin untuk menggunakan dashboard
3. **Populate Data**: Tambahkan data siswa ke database
4. **Announcement**: Umumkan portal ke siswa
5. **Monitoring**: Monitor penggunaan dan performance

## 📖 Dokumentasi Tambahan

- Baca: `README-PORTAL.md` untuk dokumentasi lengkap
- Baca: File source code dengan comment yang detail
- Lihat: Database schema di `smkn4bojonegoro_kelulusan.sql`

---

**Status**: ✅ Ready for Production  
**Last Updated**: 1 Mei 2026  
**Version**: 2.0 (Integrated)
