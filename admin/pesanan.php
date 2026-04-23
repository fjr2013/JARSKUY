<?php
session_start();
include '../koneksi.php';
include 'protect.php';

/* AMBIL DATA PESANAN */
$qPesanan = mysqli_query($conn, "
    SELECT * FROM pesanan 
    ORDER BY tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pesanan</title>
<style>
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f3f4f6;
}
.sidebar{
    width:220px;
    background:#0f172a;
    position:fixed;
    top:0;bottom:0;
    color:#fff;
    padding:20px;
}
.sidebar h2{margin-top:0}
.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    padding:10px;
    border-radius:8px;
    margin-bottom:5px;
}
.sidebar a.active,
.sidebar a:hover{background:#1e293b}

.main{
    margin-left:240px;
    padding:30px;
}
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
th{
    background:#0f172a;
    color:#fff;
    padding:12px;
    text-align:left;
}
td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}
a.detail{color:#2563eb;font-weight:bold;text-decoration:none}
</style>
</head>

<body>

<div class="sidebar">
    <h2>JARSKUY - Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="pesanan.php" class="active">Pesanan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<div class="header">
    <h1>Data Pesanan</h1>

    <!-- FIX ADMIN EMAIL (ANTI WARNING) -->
    <div>
        👤 <?= $_SESSION['admin_email'] ?? $_SESSION['email'] ?? 'Admin'; ?>
    </div>
</div>

<div class="card">
<h2>📦 Pesanan Masuk</h2>

<table>
<tr>
    <th>No</th>
    <th>Nama Pembeli</th>
    <th>Alamat</th>
    <th>Total</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php $no=1; while($p = mysqli_fetch_assoc($qPesanan)): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $p['nama_pembeli'] ?? '-' ?></td>
    <td><?= $p['alamat'] ?? '-' ?></td>
    <td>Rp <?= number_format($p['total'],0,',','.') ?></td>
    <td><?= $p['tanggal'] ?></td>
    <td>
        <a class="detail" href="detail_pesanan.php?id=<?= $p['id'] ?>">Detail</a>
    </td>
</tr>
<?php endwhile; ?>

</table>
</div>

</div>
</body>
</html>
