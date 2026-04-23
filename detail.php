<?php  
session_start();

/* DATA PRODUK */
$produkList = [
  "signature" => [
    "nama" => "MYKONOS X Set1awanade Glitch",
    "harga" => 120000,
    "img" => "css/assets/glicth.jpg"
  ],
  "midnight" => [
    "nama" => "MYKONOS X Set1awanade Invade",
    "harga" => 239000,
    "img" => "css/assets/invade.jpg"
  ],
  "rose" => [
    "nama" => "MYKONOS CALIFORNIA Signature",
    "harga" => 110000,
    "img" => "css/assets/California .jpg"
  ]
];

$key = $_GET['produk'] ?? '';

if (!isset($produkList[$key])) {
  echo "Produk tidak ditemukan";
  exit;
}

$p = $produkList[$key];
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Produk</title>

<style>
body{
  font-family:Arial;
  background:#f3f4f6;
  margin:0;
  padding:40px;
}

/* 🔥 LAYOUT */
.container{
  display:flex;
  gap:40px;
  max-width:1000px;
  margin:auto;
  background:white;
  padding:30px;
  border-radius:12px;
}

/* KIRI */
.left img{
  width:350px;
  border-radius:10px;
}

/* KANAN */
.right{flex:1;}

h2{margin-top:0;}

.price{
  font-size:22px;
  font-weight:bold;
  margin:10px 0 20px;
}

.desc{
  line-height:1.7;
  color:#333;
}

.desc p{
  margin-bottom:12px;
}

.desc b{
  display:block;
  margin-top:10px;
}

/* BUTTON */
.btn{
  background:black;
  color:white;
  padding:12px 20px;
  border:none;
  cursor:pointer;
  border-radius:8px;
  margin-top:20px;
}

.back{
  display:inline-block;
  margin-top:20px;
  text-decoration:none;
}

/* 🔔 TOAST */
#toast{
  position:fixed;
  bottom:20px;
  right:20px;
  background:#111;
  color:#fff;
  padding:12px 20px;
  border-radius:8px;
  opacity:0;
  transform:translateY(20px);
  transition:0.3s;
  z-index:9999;
}

.toast-show{
  opacity:1 !important;
  transform:translateY(0);
}

/* 📱 RESPONSIVE */
@media(max-width:768px){
  .container{
    flex-direction:column;
    text-align:center;
  }

  .left img{
    width:100%;
  }
}
</style>
</head>

<body>

<div class="container">

<!-- KIRI -->
<div class="left">
  <img src="<?= $p['img'] ?>">
</div>

<!-- KANAN -->
<div class="right">

<h2><?= $p['nama'] ?></h2>

<div class="price">
Rp <?= number_format($p['harga'],0,',','.') ?>
</div>

<div class="desc">

<?php if($key == "signature"): ?>

<p>
Extrait de Parfum unisex yang menawarkan aroma segar, fruity, dan woody yang energik.
Parfum ini menonjolkan aroma nanas bakar yang dominan, disusul sitrus bergamot,
serta sentuhan manis-smoky yang tahan lama, cocok untuk penggunaan harian.
</p>

<p><b>Karakteristik Aroma (Notes):</b></p>

<p>
Top Notes: Bergamot Sisilia, Apel, Grapefruit, Nanas Bakar.<br>
Heart Notes: Melati Kering, Lavender, Artemisia, Lily of the Valley, Lada Merah Muda.<br>
Base Notes: White Musk, Cedarwood, Moss, Amber, Patchouli.
</p>

<p><b>Kesan:</b> Segar, ceria, modern, dan memberikan energi.</p>
<p><b>Kekuatan:</b> Aroma nanas kuat dan fresh seperti soda dingin.</p>
<p><b>Ketahanan:</b> Sangat baik dan tahan lama.</p>

<?php elseif($key == "midnight"): ?>

<p>
Extrait de parfum unisex dengan aroma woody-spicy hangat, intens, dan berani.
Perpaduan lavender, kayu manis, dan vanila Madagaskar memberikan kesan maskulin.
</p>

<p><b>Notes:</b></p>

<p>
Top: Pink Pepper, Lavender, Juniper.<br>
Middle: Cinnamon, Sage.<br>
Base: Vanilla, Amber, Tonka Bean.
</p>

<?php elseif($key == "rose"): ?>

<p>
Extrait de Parfum unisex citrus aquatic yang segar, elegan, dan mewah.
Wangi lemon tea, sea breeze, dan lychee memberikan kesan clean & premium.
</p>

<p><b>Karakter:</b> Segar, juicy, aromatik.</p>

<p><b>Notes:</b></p>

<p>
Top: Lemon, Mandarin, Bergamot.<br>
Middle: Lychee, Lavender, Sea Breeze.<br>
Base: Teak Wood, Vetiver, Musk.
</p>

<p><b>Ketahanan:</b> 6–12 jam.</p>

<?php endif; ?>

</div>

<!-- BUTTON -->
<button onclick="addCartDetail('<?= $key ?>','<?= $p['nama'] ?>','<?= $p['harga'] ?>')" class="btn">
Tambah ke Keranjang
</button>

<br>

<a href="index.php" class="back">← Kembali</a>

</div>

</div>

<!-- TOAST -->
<div id="toast">✔ Ditambahkan ke keranjang</div>

<script>
function addCartDetail(key, nama, harga){

  fetch('ajax_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`action=add&key=${key}&nama=${nama}&harga=${harga}`
  })
  .then(res=>res.json())
  .then(data=>{

    let toast = document.getElementById('toast');
    toast.classList.add('toast-show');

    setTimeout(()=>{
      toast.classList.remove('toast-show');
    },2000);

  });
}
</script>

</body>
</html>