<?php
include '../koneksi.php';

$msg = '';

if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $password_baru = $_POST['password'];

    // CEK EMAIL
    $cek = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    $user = mysqli_fetch_assoc($cek);

    if ($user) {
        // UPDATE PASSWORD (versi simple)
        mysqli_query($conn, "
            UPDATE user 
            SET password='$password_baru' 
            WHERE email='$email'
        ");

        $msg = "Password berhasil diubah!";
    } else {
        $msg = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Lupa Password</title>
<style>
body{
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:#f2f2f2;
}
.box{
    background:#fff;
    padding:25px;
    width:350px;
    border-radius:10px;
}
input,button{
    width:100%;
    padding:12px;
    margin-top:10px;
}
button{
    background:black;
    color:white;
}
.msg{
    text-align:center;
    margin-bottom:10px;
    color:green;
}
.error{
    color:red;
}
</style>
</head>
<body>

<div class="box">
<h2>Reset Password</h2>

<?php if($msg): ?>
<div class="msg"><?= $msg ?></div>
<?php endif; ?>

<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password Baru" required>
<button name="reset">Reset Password</button>
</form>

<br>
<a href="login.php">← Kembali ke Login</a>
</div>

</body>
</html>