<?php
session_start();

include 'config.php';


if( isset($_SESSION['login_user'])){
  header("Location: index.php");
  exit();
}else {
 //stay logging in
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // username and password sent from form 

  $myusername = mysqli_real_escape_string($conn, $_POST['username']);
  $mypassword = mysqli_real_escape_string($conn, $_POST['password']);


  $sql = "SELECT * FROM `users` WHERE `username`= '$myusername'  AND `password`='$mypassword'";

  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_assoc($result);
    $_SESSION['login_user'] = $myusername;


    echo "Wellcome " . $row["full_name"];

    header("Location: ./index.php");
    exit();
  } else if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  } elseif (mysqli_num_rows($result) > 1) {
    echo "Please contact the admin +8801872934185";
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
</head>

<body>

  <form action="" method="post">
    <input type="text" name="username">
    <input type="password" name="password">
    <input type="submit" value="login">
  </form>

</body>

</html>