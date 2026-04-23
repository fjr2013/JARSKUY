<?php
include '../koneksi.php';
include 'protect.php';

// Statistik
$qPesanan = mysqli_query($conn, "SELECT COUNT(*) t FROM pesanan");
$totalPesanan = mysqli_fetch_assoc($qPesanan)['t'] ?? 0;

$qPendapatan = mysqli_query($conn, "SELECT SUM(total) t FROM pesanan");
$totalPendapatan = mysqli_fetch_assoc($qPendapatan)['t'] ?? 0;

$qProduk = mysqli_query($conn, "SELECT COUNT(DISTINCT produk) t FROM pesanan_detail");
$totalProduk = mysqli_fetch_assoc($qProduk)['t'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - JARSKUY</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root{
  --bg:#0f172a;         /* slate-900 */
  --panel:#111827;      /* gray-900 */
  --card:#ffffff;
  --muted:#6b7280;
  --accent:#22c55e;     /* green-500 */
}
*{box-sizing:border-box}
body{
  margin:0; font-family:Inter,Arial,sans-serif; background:#f3f4f6; color:#111827;
}
.sidebar{
  position:fixed; inset:0 auto 0 0; width:240px; background:var(--bg); color:#e5e7eb;
  padding:20px;
}
.brand{font-weight:800; letter-spacing:.5px; margin-bottom:24px}
.nav a{
  display:flex; align-items:center; gap:10px;
  color:#e5e7eb; text-decoration:none; padding:10px 12px; border-radius:8px;
  margin-bottom:6px;
}
.nav a.active,.nav a:hover{background:#1f2937}
.main{
  margin-left:240px; padding:24px;
}
.topbar{
  display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;
}
.user{
  background:#fff; padding:10px 14px; border-radius:999px; box-shadow:0 1px 6px rgba(0,0,0,.08);
}
.cards{
  display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:24px;
}
.card{
  background:var(--card); border-radius:16px; padding:20px;
  box-shadow:0 10px 20px rgba(0,0,0,.08);
}
.card h4{margin:0; color:var(--muted); font-weight:600}
.card .val{font-size:32px; font-weight:800; margin-top:10px}
.card.green{background:linear-gradient(135deg,#22c55e,#16a34a); color:#fff}
.card.green h4{color:#dcfce7}
.grid{
  display:grid; grid-template-columns:2fr 1fr; gap:20px;
}
.panel{
  background:#fff; border-radius:16px; padding:20px;
  box-shadow:0 10px 20px rgba(0,0,0,.08);
}
table{width:100%; border-collapse:collapse}
th,td{padding:10px; border-bottom:1px solid #e5e7eb}
th{color:#6b7280; text-align:left}
.badge{padding:6px 10px; border-radius:999px; background:#e5e7eb}
.btn{
  display:inline-block; padding:10px 14px; border-radius:10px;
  background:#111827; color:#fff; text-decoration:none;
}
@media (max-width:900px){
  .cards{grid-template-columns:1fr}
  .grid{grid-template-columns:1fr}
  .sidebar{position:static; width:auto}
  .main{margin-left:0}
}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="brand">JARSKUY • Admin</div>
  <nav class="nav">
    <a class="active" href="index.php">📊 Dashboard</a>
    <a href="pesanan.php">📦 Pesanan</a>
    <a href="../auth/login.php?logout=1">🚪 Logout</a>
  </nav>
</aside>

<main class="main">
  <div class="topbar">
    <h1>Dashboard</h1>
    <div class="user">👤 <?= htmlspecialchars($_SESSION['email'] ?? 'Admin'); ?></div>
  </div>

  <section class="cards">
    <div class="card">
      <h4>Total Pesanan</h4>
      <div class="val"><?= $totalPesanan ?></div>
    </div>
    <div class="card">
      <h4>Produk Terjual</h4>
      <div class="val"><?= $totalProduk ?></div>
    </div>
    <div class="card green">
      <h4>Total Pendapatan</h4>
      <div class="val">Rp <?= number_format($totalPendapatan) ?></div>
    </div>
  </section>

  <section class="grid">
    <div class="panel">
      <h3>Pesanan Terbaru</h3>
      <table>
        <tr><th>Nama</th><th>Total</th><th>Tanggal</th></tr>
        <?php
        $last = mysqli_query($conn,"SELECT * FROM pesanan ORDER BY id DESC LIMIT 5");
        while($r=mysqli_fetch_assoc($last)){ ?>
          <tr>
            <td><?= htmlspecialchars($r['nama_pembeli']) ?></td>
            <td>Rp <?= number_format($r['total']) ?></td>
            <td><?= $r['tanggal'] ?></td>
          </tr>
        <?php } ?>
      </table>
      <br>
      <a class="btn" href="pesanan.php">Lihat semua</a>
    </div>

    <div class="panel">
      <h3>Ringkas</h3>
      <p><span class="badge">Hari ini</span> pantau pesanan & pendapatan.</p>
      <p>Gunakan menu <b>Pesanan</b> untuk detail item.</p>
    </div>
  </section>
</main>

</body>
</html>
