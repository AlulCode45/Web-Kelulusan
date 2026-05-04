<?php
session_destroy();
session_start();

if (empty($_SESSION['admin'])) {
    header("location: login.php");
    exit;
}

include '../inc/config.php';

// Get students data
$query = "SELECT * FROM data_kelulusan ORDER BY nisn DESC";
$result = mysqli_query($conn, $query);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMKN 4 Bojonegoro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --muted: #f3f4f6;
            --border: #e5e7eb;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #f9fafb;
            color: var(--text-dark);
        }

        header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-img {
            height: 2.5rem;
            width: auto;
        }

        .logo-text h1 {
            font-size: 1.125rem;
            font-weight: 700;
            margin: 0;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: var(--text-light);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            text-align: right;
            font-size: 0.9rem;
        }

        .user-info p {
            margin: 0;
            color: var(--text-light);
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        h2 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        .form-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .table-container {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--muted);
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        tbody tr:hover {
            background: var(--muted);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-lulus {
            background: #dcfce7;
            color: #166534;
        }

        .status-tidak-lulus {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            text-decoration: none;
            color: var(--text-dark);
            cursor: pointer;
        }

        .pagination a:hover {
            background: var(--muted);
        }

        .pagination .active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="../assets/logo.png" alt="Logo SMKN 4" class="logo-img">
            <div class="logo-text">
                <h1>SMK Negeri 4 Bojonegoro</h1>
                <p>Dashboard Admin</p>
            </div>
        </div>
        <div class="header-right">
            <div class="user-info">
                <p>Admin Login</p>
            </div>
            <form method="POST" action="logout.php" style="margin: 0;">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <div class="container">
        <h2>📊 Manajemen Data Siswa</h2>

        <div id="successAlert" class="alert alert-success">
            Data berhasil disimpan/diperbarui!
        </div>

        <div id="errorAlert" class="alert alert-error">
            Terjadi kesalahan saat memproses data.
        </div>

        <!-- Form Tambah/Edit -->
        <div class="form-card">
            <div class="form-title">Tambah Data Siswa Baru</div>
            <form method="POST" action="tambahSiswa.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nisn">NISN *</label>
                        <input type="text" id="nisn" name="nisn" placeholder="10 digit NISN" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" placeholder="Nama siswa" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="jurusan">Kompetensi Keahlian *</label>
                        <select id="jurusan" name="jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Rekayasa Perangkat Lunak 1">Rekayasa Perangkat Lunak 1</option>
                            <option value="Rekayasa Perangkat Lunak 2">Rekayasa Perangkat Lunak 2</option>
                            <option value="Teknik Pengelasan 1">Teknik Pengelasan 1</option>
                            <option value="Teknik Pengelasan 2">Teknik Pengelasan 2</option>
                            <option value="Agrobisnis Ternak Ruminansia 1">Agrobisnis Ternak Ruminansia 1</option>
                            <option value="Agrobisnis Ternak Ruminansia 2">Agrobisnis Ternak Ruminansia 2</option>
                            <option value="Tata Boga 1">Tata Boga 1</option>
                            <option value="Tata Boga 2">Tata Boga 2</option>
                            <option value="Perhotelan 1">Perhotelan 1</option>
                            <option value="Perhotelan 2">Perhotelan 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_lulus">Status Kelulusan *</label>
                        <select id="status_lulus" name="status_lulus" required>
                            <option value="1">LULUS</option>
                            <option value="0">TIDAK LULUS</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kompetensi Keahlian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $key => $student): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><code><?php echo htmlspecialchars($student['nisn']); ?></code></td>
                                <td><?php echo htmlspecialchars($student['nama']); ?></td>
                                <td><?php echo htmlspecialchars($student['jurusan']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo ($student['status_lulus'] == 1 ? 'status-lulus' : 'status-tidak-lulus'); ?>">
                                        <?php echo ($student['status_lulus'] == 1 ? 'LULUS' : 'TIDAK LULUS'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" action="hapusData.php" style="margin: 0;" onsubmit="return confirm('Yakin hapus data ini?');">
                                            <input type="hidden" name="nisn" value="<?php echo htmlspecialchars($student['nisn']); ?>">
                                            <button type="submit" class="btn btn-danger">🗑 Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-light);">Belum ada data siswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Show success message if redirected with success parameter
        if (new URLSearchParams(window.location.search).get('success')) {
            document.getElementById('successAlert').classList.add('show');
            setTimeout(() => {
                document.getElementById('successAlert').classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
