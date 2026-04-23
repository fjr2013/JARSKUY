<?php
session_start();

/* JIKA SUDAH LOGIN, JANGAN BISA BUKA LOGIN LAGI */
if (isset($_SESSION['admin'])) {
    header("Location: ../admin/index.php");
    exit;
}

include '../koneksi.php';

/* DEBUG (boleh tetap ada) */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';


if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "
        SELECT * FROM user 
        WHERE TRIM(email) = '$email'
        AND role = 'admin'
        LIMIT 1
    ");

    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($query);

    if ($user) {
       if ($password === $user['password']) {

            $_SESSION['admin'] = [
                'id'    => $user['id'],
                'email' => trim($user['email']),
                'role'  => $user['role']
            ];

            header("Location: ../admin/index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        body {
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial, sans-serif;
            background:#f2f2f2;
        }
        .box {
            width:350px;
            background:#fff;
            padding:25px;
            border-radius:10px;
            box-shadow:0 10px 30px rgba(0,0,0,.15);
        }
        h2 {
            text-align:center;
            margin-bottom:20px;
        }
        input, button {
            width:100%;
            padding:12px;
            margin-top:10px;
            font-size:14px;
        }
        button {
            background:black;
            color:white;
            border:none;
            cursor:pointer;
        }
        .error {
            background:#ffdede;
            color:#a00;
            padding:10px;
            text-align:center;
            margin-bottom:10px;
            border-radius:5px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Login Admin</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p style="text-align:center; margin-top:10px;">
  <a href="lupa_password.php">Lupa Password?</a>
</p>
</div>

</body>
</html>
