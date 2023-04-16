<?php
require 'config/config.php';

// Declaring variables
$username = "";
$password = "";

if(isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $_SESSION['username'] = $username; // Stores username into session.
    
    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query);

    if($check_login_query == 1) {
        $row = mysqli_fetch_array($check_database_query);
        $username = $row['username'];
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PantherNet - Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" value="
            <?php
                if(isset($_SESSION['username'])) {
                    echo $_SESSION['username'];
                }
            ?>
        ">
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <input type="submit" name="signin" value="Login">
    </form>
</body>
</html>