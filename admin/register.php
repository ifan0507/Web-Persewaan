<?php
include("conn.php");
if (isset($_POST['daftar'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = mysqli_query($db, "INSERT INTO `user` (id_user,username,password) VALUES (null,'$username', '$password')");
    if ($query) {
        echo "<script>alert('berhasil');</script>";
    } else {
        echo "<script>alert('gagal');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="username">
        <input type="text" name="password">
        <button type="submit" name="daftar">Daftar</button>
    </form>
</body>

</html>