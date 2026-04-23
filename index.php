<?php
session_start();

/* DATA PRODUK */
$produkList = [
  "signature" => [
    "nama" => "MYKONOS X Set1awanade Glitch",
    "harga" => 120000,
    "deskripsi" => "Top Notes: sweet, smoky, pineapple.",
    "img" => "css/assets/glicth.jpg"
  ],
  "midnight" => [
    "nama" => "MYKONOS X Set1awanade Invade",
    "harga" => 239000,
    "deskripsi" => "Top Notes: pink pepper, lavender.",
    "img" => "css/assets/invade.jpg"
  ],
  "rose" => [
    "nama" => "MYKONOS CALIFORNIA Signature",
    "harga" => 110000,
    "deskripsi" => "Fresh citrus aquatic premium.",
    "img" => "css/assets/California .jpg"
  ]
];

/* INIT CART */
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>JARSKUY PARFUME</title>

<style>
body{font-family:Arial;margin:0;background:#f3f4f6}

header{
  background:#111;color:#fff;padding:20px;
  display:flex;justify-content:space-between;
}

.grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;
}

.card{
  background:#fff;
  padding:15px;
  border-radius:10px;
  cursor:pointer;
}
.card:hover{transform:scale(1.03)}

.btn{
  background:black;color:white;padding:8px;border:none;cursor:pointer;
}

/* CART */
.cart-overlay{
  position:fixed;
  top:0;left:0;
  width:100%;height:100%;
  background:rgba(0,0,0,0.4);
  display:none;
}

.cart-box{
  position:fixed;
  top:0;
  right:-350px;
  width:300px;
  height:100%;
  background:#fff;
  padding:20px;
  overflow-y:auto;
  transition:0.3s;
}
.cart-box.active{right:0;}

/* TOAST */
#toast{
  position:fixed;
  bottom:20px;
  right:20px;
  background:#111;
  color:#fff;
  padding:12px 20px;
  border-radius:8px;
  opacity:0;
  transition:0.3s;
  z-index:9999;
}

/* ANIMASI */
.fly-img{
  position:fixed;
  width:60px;
  z-index:9999;
  pointer-events:none;
  transition:all 0.7s ease;
}
</style>
</head>

<body>

<header>
<h2>JARSKUY PARFUME</h2>

<div onclick="openCart()" style="cursor:pointer">
🛒 <span id="cartCount">0</span>
</div>
</header>

<div id="overlay" class="cart-overlay"></div>

<div id="cartBox" class="cart-box">
<h3>Keranjang</h3>
<p>Keranjang kosong</p>
</div>

<main style="padding:20px">

<div class="grid">
<?php foreach($produkList as $key=>$p): ?>

<div class="card" onclick="window.location='detail.php?produk=<?= $key ?>'">

<img src="<?= $p['img'] ?>" class="product-img" width="100%">

<h3><?= $p['nama'] ?></h3>

<p><?= nl2br($p['deskripsi']) ?></p>

<strong>Rp <?= number_format($p['harga']) ?></strong>

<button onclick="event.stopPropagation(); addCart(this,'<?= $key ?>','<?= $p['nama'] ?>','<?= $p['harga'] ?>')" class="btn">
Tambah
</button>

</div>

<?php endforeach; ?>
</div>

</main>

<!-- TOAST -->
<div id="toast">✔ Ditambahkan ke keranjang</div>

<script>
function openCart(){
  document.getElementById('cartBox').classList.add('active');
  document.getElementById('overlay').style.display='block';
}

document.getElementById('overlay').onclick = ()=>{
  document.getElementById('cartBox').classList.remove('active');
  document.getElementById('overlay').style.display='none';
};

/* TOAST */
function showToast(){
  let toast = document.getElementById('toast');
  toast.style.opacity = 1;
  setTimeout(()=>{toast.style.opacity = 0;},2000);
}

/* ANIMASI */
function flyToCart(imgElement){
  let cart = document.querySelector('[onclick="openCart()"]');

  let img = imgElement.cloneNode(true);
  img.classList.add('fly-img');

  let rect = imgElement.getBoundingClientRect();
  img.style.top = rect.top + 'px';
  img.style.left = rect.left + 'px';

  document.body.appendChild(img);

  let cartRect = cart.getBoundingClientRect();

  setTimeout(()=>{
    img.style.top = cartRect.top + 'px';
    img.style.left = cartRect.left + 'px';
    img.style.opacity = 0;
    img.style.transform = "scale(0.3)";
  },10);

  setTimeout(()=>{img.remove();},700);
}

/* AJAX CART */
function addCart(btn, key, nama, harga){

  let card = btn.closest('.card');
  let img = card.querySelector('.product-img');

  flyToCart(img);
  showToast();

  fetch('ajax_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`action=add&key=${key}&nama=${nama}&harga=${harga}`
  })
  .then(res=>res.json())
  .then(updateCart);
}

function plusCart(key){
  fetch('ajax_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`action=plus&key=${key}`
  })
  .then(res=>res.json())
  .then(updateCart);
}

function minusCart(key){
  fetch('ajax_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`action=minus&key=${key}`
  })
  .then(res=>res.json())
  .then(updateCart);
}

function updateCart(cart){
  let box = document.getElementById('cartBox');
  let total = 0;
  let html = '<h3>Keranjang</h3>';

  for(let key in cart){
    let i = cart[key];
    total += i.qty;

    html += `
    <div>
      <b>${i.nama}</b><br>
      Rp ${i.harga}<br>

      <button onclick="minusCart('${key}')">-</button>
      ${i.qty}
      <button onclick="plusCart('${key}')">+</button>
    </div><hr>
    `;
  }

  if(total == 0){
    html += "Keranjang kosong";
  }else{
    html += `<a href="checkout.php" class="btn">Checkout</a>`;
  }

  box.innerHTML = html;
  document.getElementById('cartCount').innerText = total;

  openCart();
}
</script>

</body>
</html>