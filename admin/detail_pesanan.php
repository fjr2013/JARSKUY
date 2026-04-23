<?php
include '../koneksi.php';
include 'protect.php';

if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* ================= AMBIL PESANAN ================= */
$qPesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id'");
if (!$qPesanan) {
    die("Query pesanan gagal: " . mysqli_error($conn));
}

$p = mysqli_fetch_assoc($qPesanan);
if (!$p) {
    echo "Pesanan tidak ditemukan";
    exit;
}

/* ================= DETAIL PRODUK ================= */
$qDetail = mysqli_query($conn, "SELECT * FROM pesanan_detail WHERE pesanan_id='$id'");
if (!$qDetail) {
    die("Query detail gagal: " . mysqli_error($conn));
}

/* ================= TANDAI DIBAYAR ================= */
if (isset($_POST['lunas'])) {
    mysqli_query($conn, "
        UPDATE pesanan 
        SET status_pembayaran='dibayar'
        WHERE id='$id'
    ");
    header("Location: detail_pesanan.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Pesanan</title>
<style>
body{font-family:Arial;background:#f3f4f6;margin:0}
.container{
    max-width:1000px;margin:30px auto;background:#fff;
    padding:25px;border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.08)
}
.badge{padding:6px 14px;border-radius:20px;font-size:13px;font-weight:bold}
.pending{background:#fee2e2;color:#b91c1c}
.dibayar{background:#dcfce7;color:#166534}
table{width:100%;border-collapse:collapse;margin-top:20px}
th{background:#0f172a;color:#fff;padding:12px;text-align:left}
td{padding:12px;border-bottom:1px solid #e5e7eb}
.total{text-align:right;font-weight:bold}
.btn{padding:10px 16px;border-radius:8px;border:none;font-weight:bold;cursor:pointer}
.btn-back{background:#e5e7eb}
.btn-paid{background:#22c55e;color:#fff}
</style>
</head>
<body>

<div class="container">
<h2>🧾 Detail Pesanan</h2>

<p><b>ID Pesanan:</b> <?= $p['id'] ?></p>
<p><b>Tanggal:</b> <?= $p['tanggal'] ?></p>
<p><b>Metode Pembayaran:</b> <?= $p['metode_pembayaran'] ?? '-' ?></p>

<!-- ✅ EKSPEDISI (FINAL FIX) -->
<p><b>Ekspedisi:</b> <?= $p['ekspedisi'] ?? '-' ?></p>

<p><b>Status Pembayaran:</b>
<?php
$status = $p['status_pembayaran'] ?? 'pending';
if ($status === 'dibayar') {
    echo '<span class="badge dibayar">DIBAYAR</span>';
} else {
    echo '<span class="badge pending">PENDING</span>';
}
?>
</p>

<hr>

<table>
<tr>
  <th>Produk</th>
  <th>Harga</th>
  <th>Qty</th>
  <th>Subtotal</th>
</tr>

<?php while ($d = mysqli_fetch_assoc($qDetail)): ?>
<tr>
  <td><?= htmlspecialchars($d['produk']) ?></td>
  <td>Rp <?= number_format($d['harga'],0,',','.') ?></td>
  <td><?= $d['qty'] ?></td>
  <td>Rp <?= number_format($d['subtotal'],0,',','.') ?></td>
</tr>
<?php endwhile; ?>

<tr>
  <td colspan="3" class="total">Total</td>
  <td class="total">Rp <?= number_format($p['total'],0,',','.') ?></td>
</tr>
</table>

<br>

<a href="pesanan.php" class="btn btn-back">⬅ Kembali</a>

<?php if (($p['status_pembayaran'] ?? 'pending') !== 'dibayar'): ?>
<form method="post" style="display:inline">
<button name="lunas" class="btn btn-paid">✔ Tandai Dibayar</button>
</form>
<?php endif; ?>

</div>
</body>
</html>
